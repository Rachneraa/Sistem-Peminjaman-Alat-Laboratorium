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
        $tools = Tool::with('category')->orderBy('nama_alat')->get();

        return view('admin.denda.index', compact('tools'));
    }

    /**
     * Export pengaturan denda per alat ke CSV.
     */
    public function export()
    {
        $tools = Tool::with('category')->orderBy('nama_alat')->get();

        $filename = 'pengaturan_denda_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($tools) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM agar karakter tampil benar di Excel.
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fwrite($file, "sep=,\n");

            fputcsv($file, ['ID Alat', 'Nama Alat', 'Kategori', 'Stok Total', 'Denda per Hari']);

            foreach ($tools as $tool) {
                fputcsv($file, [
                    $tool->id,
                    $tool->nama_alat,
                    $tool->category->nama_kategori ?? '-',
                    $tool->stok_total,
                    $tool->denda_per_hari ?? 5000,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Simpan pengaturan denda per alat.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'tools' => 'required|array|min:1',
            'tools.*.denda_per_hari' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['tools'] as $toolId => $data) {
                Tool::whereKey($toolId)->update([
                    'denda_per_hari' => $data['denda_per_hari'],
                ]);
            }

            ActivityLog::createLog(
                auth()->id(),
                'Mengupdate pengaturan denda per alat',
                null
            );

            DB::commit();

            return redirect()->route('admin.denda.index')
                ->with('success', 'Pengaturan denda berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menyimpan pengaturan denda: ' . $e->getMessage());
        }
    }
}