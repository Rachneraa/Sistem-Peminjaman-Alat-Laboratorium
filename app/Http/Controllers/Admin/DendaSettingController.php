<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DendaSettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan denda per alat.
     */
    public function index()
    {
        $tools = Tool::with('category')->orderBy('nama_alat')->paginate(10);

        return view('admin.denda.index', compact('tools'));
    }

    /**
     * Export pengaturan denda per alat ke CSV.
     */
    public function export()
    {
        $filename = 'pengaturan_denda_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM agar karakter tampil benar di Excel.
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fwrite($file, "sep=,\n");

            fputcsv($file, ['ID Alat', 'Nama Alat', 'Kategori', 'Stok Total', 'Denda per Hari']);

            Tool::query()
                ->with(['category:id,nama_kategori'])
                ->select(['id', 'nama_alat', 'kategori_id', 'stok', 'stok_rusak', 'stok_perbaikan', 'denda_per_hari'])
                ->orderBy('id')
                ->chunkById(500, function ($tools) use ($file) {
                    foreach ($tools as $tool) {
                        fputcsv($file, [
                            $tool->id,
                            $tool->nama_alat,
                            $tool->category->nama_kategori ?? '-',
                            $tool->stok_total,
                            $tool->denda_per_hari ?? 5000,
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Simpan pengaturan denda per alat.
     */
    public function update(Request $request)
    {
        DB::beginTransaction();

        try {
            // Mode simpan per-card (single tool).
            if ($request->filled('tool_id')) {
                $validated = $request->validate([
                    'tool_id' => 'required|integer|exists:tools,id',
                    'denda_per_hari' => 'required|numeric|min:0',
                ]);

                Tool::whereKey($validated['tool_id'])->update([
                    'denda_per_hari' => $validated['denda_per_hari'],
                ]);
            } else {
                // Tetap kompatibel dengan mode simpan massal.
                $validated = $request->validate([
                    'tools' => 'required|array|min:1',
                    'tools.*.denda_per_hari' => 'required|numeric|min:0',
                ]);

                foreach ($validated['tools'] as $toolId => $data) {
                    Tool::whereKey($toolId)->update([
                        'denda_per_hari' => $data['denda_per_hari'],
                    ]);
                }
            }

            ActivityLog::createLog(
                auth()->id(),
                'Mengupdate pengaturan denda per alat',
                null
            );

            DB::commit();

            return redirect()->route('admin.denda.index', ['page' => $request->input('page', 1)])
                ->with('success', 'Pengaturan denda berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menyimpan pengaturan denda: ' . $e->getMessage());
        }
    }
}