<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReturnModel;
use App\Models\Borrowing;
use App\Models\Tool;
use App\Models\ActivityLog;
use App\Helpers\DendaHelper;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    /**
     * Menampilkan daftar pengembalian
     */
    public function index(Request $request)
    {
        $query = ReturnModel::with(['borrowing.user', 'borrowing.borrowingDetails.tool']);

        // Filter by denda (ada/tidak ada)
        if ($request->filled('denda')) {
            if ($request->denda == 'ada') {
                $query->where('denda', '>', 0);
            } elseif ($request->denda == 'tidak_ada') {
                $query->where('denda', '=', 0);
            }
        }

        // Search by user name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('borrowing.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $returns = $query->latest()->paginate(10)->withQueryString();
        return view('admin.returns.index', compact('returns'));
    }

    /**
     * Menampilkan form tambah pengembalian
     */
    public function create()
    {
        // Ambil peminjaman yang sudah disetujui dan belum dikembalikan
        $borrowings = Borrowing::with(['user', 'borrowingDetails.tool'])
            ->whereIn('status', ['disetujui', 'menunggu_pengembalian'])
            ->whereDoesntHave('return')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();

        // Siapkan data untuk JavaScript
        $borrowingsData = $borrowings->map(function ($borrowing) {
            return [
                'id' => $borrowing->id,
                'user' => [
                    'name' => $borrowing->user->name,
                ],
                'tanggal_pinjam' => $borrowing->tanggal_pinjam->format('d/m/Y'),
                'tanggal_selesai' => $borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : null,
                'borrowing_details' => $borrowing->borrowingDetails->map(function ($detail) {
                    return [
                        'tool' => [
                            'nama_alat' => $detail->tool->nama_alat,
                        ],
                        'jumlah' => $detail->jumlah,
                    ];
                }),
            ];
        });

        return view('admin.returns.create', compact('borrowings', 'borrowingsData'));
    }

    /**
     * Menyimpan pengembalian baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'tanggal_kembali' => 'required|date',
            'denda_kerusakan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $borrowing = Borrowing::findOrFail($validated['borrowing_id']);

            // Validasi: hanya peminjaman yang disetujui atau menunggu pengembalian
            if (!in_array($borrowing->status, ['disetujui', 'menunggu_pengembalian'])) {
                return back()->with('error', 'Hanya peminjaman yang disetujui atau menunggu pengembalian yang bisa dikembalikan.')
                    ->withInput();
            }

            // Hitung denda
            $tanggal_kembali = \Carbon\Carbon::parse($validated['tanggal_kembali']);
            $tanggal_selesai = $borrowing->tanggal_selesai ?? $borrowing->jatuh_tempo;
            $dendaPerHari = $borrowing->calculateDendaPerHariTotal();
            $dendaData = DendaHelper::hitungDenda($tanggal_kembali, $tanggal_selesai, $dendaPerHari);
            $dendaKerusakan = $validated['denda_kerusakan'] ?? 0;

            // Kembalikan stok alat
            foreach ($borrowing->borrowingDetails as $detail) {
                $tool = Tool::find($detail->tool_id);
                $tool->increment('stok', $detail->jumlah);
                $tool->updateStatusFromStock();
            }

            // Buat record pengembalian
            $return = ReturnModel::create([
                'borrowing_id' => $borrowing->id,
                'tanggal_kembali' => $tanggal_kembali->format('Y-m-d'),
                'denda' => $dendaData['denda'],
                'terlambat_hari' => $dendaData['terlambat_hari'],
                'denda_kerusakan' => $dendaKerusakan,
                'keterangan' => $validated['keterangan'],
            ]);

            // Update status peminjaman
            $borrowing->update(['status' => 'dikembalikan']);

            ActivityLog::createLog(
                auth()->id(),
                'Membuat pengembalian baru ID: ' . $return->id,
                $return
            );

            DB::commit();
            return redirect()->route('admin.returns.index')
                ->with('success', 'Pengembalian berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat pengembalian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Menampilkan detail pengembalian
     */
    public function show(ReturnModel $return)
    {
        $return->load(['borrowing.user', 'borrowing.borrowingDetails.tool.category']);
        return view('admin.returns.show', compact('return'));
    }

    /**
     * Menampilkan form edit pengembalian
     */
    public function edit(ReturnModel $return)
    {
        $return->load(['borrowing.user', 'borrowing.borrowingDetails.tool.category']);
        return view('admin.returns.edit', compact('return'));
    }

    /**
     * Update pengembalian
     */
    public function update(Request $request, ReturnModel $return)
    {
        $validated = $request->validate([
            'tanggal_kembali' => 'required|date',
            'denda' => 'nullable|numeric|min:0',
            'terlambat_hari' => 'nullable|integer|min:0',
            'denda_kerusakan' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $return->update([
                'tanggal_kembali' => $validated['tanggal_kembali'],
                'denda' => $validated['denda'] ?? $return->denda,
                'terlambat_hari' => $validated['terlambat_hari'] ?? $return->terlambat_hari,
                'denda_kerusakan' => $validated['denda_kerusakan'] ?? 0,
                'keterangan' => $validated['keterangan'],
            ]);

            ActivityLog::createLog(
                auth()->id(),
                'Mengupdate pengembalian ID: ' . $return->id,
                $return
            );

            DB::commit();
            return redirect()->route('admin.returns.index')
                ->with('success', 'Pengembalian berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengupdate pengembalian: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Hapus pengembalian
     */
    public function destroy(ReturnModel $return)
    {
        DB::beginTransaction();
        try {
            $borrowing = $return->borrowing;

            // Kembalikan status peminjaman menjadi disetujui
            if ($borrowing->status === 'dikembalikan') {
                // Kurangi stok kembali (karena pengembalian dihapus)
                foreach ($borrowing->borrowingDetails as $detail) {
                    $tool = Tool::find($detail->tool_id);
                    $tool->decrement('stok', $detail->jumlah);
                    $tool->updateStatusFromStock();
                }
                $borrowing->update(['status' => 'disetujui']);
            }

            $returnId = $return->id;
            $return->delete();

            ActivityLog::createLog(
                auth()->id(),
                'Menghapus pengembalian ID: ' . $returnId,
                null
            );

            DB::commit();
            return redirect()->route('admin.returns.index')
                ->with('success', 'Pengembalian berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus pengembalian: ' . $e->getMessage());
        }
    }
}

