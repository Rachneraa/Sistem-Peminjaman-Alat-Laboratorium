<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\ReturnModel;
use App\Models\ReturnDetail;
use App\Models\Tool;
use App\Models\ActivityLog;
use App\Services\NotificationService;
use App\Helpers\DendaHelper;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    /**
     * Menampilkan halaman memantau pengembalian
     */
    public function index(Request $request)
    {
        // Tampilkan yang menunggu persetujuan pengembalian DAN yang masih aktif (disetujui)
        // Agar petugas bisa memantau denda dan keterlambatan
        $query = Borrowing::with(['user', 'borrowingDetails.tool.category', 'return'])
            ->whereIn('status', ['menunggu_pengembalian', 'disetujui']);

        // Filter berdasarkan status terlambat
        if ($request->has('filter')) {
            if ($request->filter == 'terlambat') {
                $query->where(function ($q) {
                    $q->whereNotNull('tanggal_selesai')
                        ->where('tanggal_selesai', '<', now())
                        ->orWhere(function ($q2) {
                            $q2->whereNotNull('jatuh_tempo')
                                ->where('jatuh_tempo', '<', now());
                        });
                });
            }
        }

        $borrowings = $query->latest()->paginate(15);

        return view('petugas.returns.index', compact('borrowings'));
    }

    /**
     * Proses pengembalian alat (menyetujui pengembalian)
     */
    public function processReturn(Request $request, Borrowing $borrowing)
    {
        // Validasi: yang menunggu pengembalian atau yang aktif (untuk return manual)
        if (!in_array($borrowing->status, ['menunggu_pengembalian', 'disetujui'])) {
            return back()->with('error', 'Hanya pengembalian yang menunggu persetujuan atau aktif yang bisa diproses.');
        }

        $validated = $request->validate([
            'tanggal_kembali' => 'required|date',
            'abaikan_denda' => 'nullable|boolean',
            'alasan_abaikan_denda' => 'nullable|required_if:abaikan_denda,1|string|min:8',
            'jaminan_dikembalikan' => 'nullable|boolean',
            'keterangan' => 'nullable|string',
            'tools' => 'required|array',
            'tools.*.tool_id' => 'required|exists:tools,id',
            'tools.*.jumlah_kembali' => 'required|integer|min:0',
            'tools.*.jumlah_rusak' => 'required|integer|min:0',
            'tools.*.persen_kerusakan' => 'required|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();
        try {
            // Hitung denda keterlambatan
            $tanggal_kembali = \Carbon\Carbon::parse($validated['tanggal_kembali']);
            $tanggal_selesai = $borrowing->tanggal_selesai ?? $borrowing->jatuh_tempo;

            $dendaPerHari = $borrowing->calculateDendaPerHariTotal();
            $dendaData = DendaHelper::hitungDenda($tanggal_kembali, $tanggal_selesai, $dendaPerHari);
            $dendaKeterlambatanAwal = (float) $dendaData['denda'];
            $abaikanDenda = $request->boolean('abaikan_denda');
            $alasanAbaikanDenda = $abaikanDenda ? trim((string) ($validated['alasan_abaikan_denda'] ?? '')) : null;
            $dendaKeterlambatanFinal = $abaikanDenda ? 0 : $dendaKeterlambatanAwal;

            // Hitung total denda kerusakan dari semua alat
            $totalDendaKerusakan = 0;
            $returnDetailsData = [];

            foreach ($validated['tools'] as $toolData) {
                $tool = Tool::find($toolData['tool_id']);
                if (!$tool) continue;

                $jumlahKembali = (int) $toolData['jumlah_kembali'];
                $jumlahRusak = (int) $toolData['jumlah_rusak'];
                $persenKerusakan = (float) $toolData['persen_kerusakan'];
                $hargaAsli = (float) ($tool->harga_asli ?? 0);

                // Hitung denda kerusakan untuk item ini
                // Rumus: harga_asli × persen_kerusakan × jumlah_rusak
                $dendaKerusakanItem = ($hargaAsli * $persenKerusakan / 100) * $jumlahRusak;
                $totalDendaKerusakan += $dendaKerusakanItem;

                // Simpan data untuk return_details
                $returnDetailsData[] = [
                    'tool_id' => $tool->id,
                    'jumlah_kembali' => $jumlahKembali,
                    'jumlah_rusak' => $jumlahRusak,
                    'persen_kerusakan' => $persenKerusakan,
                    'denda_kerusakan_item' => $dendaKerusakanItem,
                ];

                // Update stok alat
                // Stok bagus bertambah sesuai jumlah_kembali
                $tool->increment('stok', $jumlahKembali);
                
                // Stok rusak bertambah sesuai jumlah_rusak
                if ($jumlahRusak > 0) {
                    $tool->increment('stok_rusak', $jumlahRusak);
                }
                
                $tool->updateStatusFromStock();
            }

            // Buat record pengembalian
            $return = ReturnModel::create([
                'borrowing_id' => $borrowing->id,
                'tanggal_kembali' => $tanggal_kembali->format('Y-m-d'),
                'denda' => $dendaKeterlambatanFinal,
                'denda_keterlambatan_awal' => $dendaKeterlambatanAwal,
                'terlambat_hari' => $dendaData['terlambat_hari'],
                'denda_kerusakan' => $totalDendaKerusakan,
                'denda_diabaikan' => $abaikanDenda,
                'alasan_abaikan_denda' => $alasanAbaikanDenda,
                'keterangan' => $validated['keterangan'],
            ]);

            // Simpan return_details
            foreach ($returnDetailsData as $detailData) {
                $return->returnDetails()->create($detailData);
            }

            // Update status peminjaman
            $jaminanDikembalikan = $request->boolean('jaminan_dikembalikan');

            $borrowing->update([
                'status' => 'dikembalikan',
                'ktp_dikembalikan_at' => $jaminanDikembalikan ? now() : $borrowing->ktp_dikembalikan_at,
                'ktp_dikembalikan_oleh' => $jaminanDikembalikan ? auth()->id() : $borrowing->ktp_dikembalikan_oleh,
            ]);

            ActivityLog::createLog(
                auth()->id(),
                'Memproses pengembalian peminjaman ID: ' . $borrowing->id,
                $return
            );

            // Notifikasi ke peminjam
            NotificationService::notifyReturnProcessed($return);

            DB::commit();
            $totalDenda = $dendaKeterlambatanFinal + $totalDendaKerusakan;
            $message = 'Pengembalian berhasil diproses.';

            $dendaDetails = [];
            if ($dendaKeterlambatanFinal > 0) {
                $dendaDetails[] = 'Keterlambatan: Rp ' . number_format($dendaKeterlambatanFinal, 0, ',', '.') . ' (' . $dendaData['terlambat_hari'] . ' hari)';
            } elseif ($abaikanDenda && $dendaKeterlambatanAwal > 0) {
                $dendaDetails[] = 'Keterlambatan diabaikan: Rp ' . number_format($dendaKeterlambatanAwal, 0, ',', '.');
            }
            if ($totalDendaKerusakan > 0) {
                $dendaDetails[] = 'Kerusakan: Rp ' . number_format($totalDendaKerusakan, 0, ',', '.');
            }

            if (count($dendaDetails) > 0) {
                $message .= ' Total Denda: Rp ' . number_format($totalDenda, 0, ',', '.') . ' (' . implode(', ', $dendaDetails) . ')';
            }

            return redirect()->route('petugas.borrowings.show', $borrowing)
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pengembalian: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan semua pengembalian
     */
    public function all(Request $request)
    {
        $query = ReturnModel::with(['borrowing.user', 'borrowing.borrowingDetails.tool']);

        // Filter berdasarkan denda
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

        $returns = $query->latest()->paginate(15)->withQueryString();

        return view('petugas.returns.all', compact('returns'));
    }
}

