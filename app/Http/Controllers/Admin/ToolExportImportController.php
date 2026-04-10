<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ToolExportImportController extends Controller
{
    /**
     * Export tools to CSV
     */
    public function export()
    {
        $filename = 'tools_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add separator hint for Excel
            fwrite($file, "sep=,\n");

            // Header row
            fputcsv($file, ['ID', 'Nama Alat', 'Kategori', 'Stok Total', 'Stok Tersedia', 'Status', 'Deskripsi']);

            // Stream rows per chunk to reduce memory usage.
            Tool::query()
                ->with(['category:id,nama_kategori'])
                ->select(['id', 'nama_alat', 'kategori_id', 'stok', 'stok_rusak', 'stok_perbaikan', 'status', 'deskripsi'])
                ->orderBy('id')
                ->chunkById(500, function ($tools) use ($file) {
                    foreach ($tools as $tool) {
                        fputcsv($file, [
                            $tool->id,
                            $tool->nama_alat,
                            $tool->category->nama_kategori ?? '-',
                            $tool->stok_total,
                            $tool->stok_tersedia,
                            ucfirst($tool->status),
                            $tool->deskripsi ?? '-',
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import tools from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file');
        $file = $request->file('file');

        // Detect delimiter
        $handle = fopen($file->getRealPath(), 'r');
        $preview = fread($handle, 1000);
        $delimiter = substr_count($preview, ';') > substr_count($preview, ',') ? ';' : ',';
        fclose($handle);

        $handle = fopen($file->getRealPath(), 'r');

        // Skip UTF-8 BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        // Read first line - could be sep=, or header
        $firstLine = fgets($handle);

        // Skip sep=, line if present
        if (strpos($firstLine, 'sep=') === 0) {
            // Read actual header
            fgetcsv($handle, 0, $delimiter);
        }
        // If first line is not sep=, it's the header. 
        // NOTE: fgets moved pointer. if first line WAS header (not sep=), we need to ensure we don't accidentally skip data.
        // But since we want to skip header anyway:
        // Case 1: sep= line exists. fgets read it. header is next. fgetcsv skips header. Pointer at data. OK.
        // Case 2: No sep= line. fgets read header. Pointer at data. OK.

        $imported = 0;
        $errors = [];

        while (($data = fgetcsv($handle, 0, $delimiter)) !== false) {
            // Skip empty rows
            if (empty(array_filter($data))) {
                continue;
            }

            // Skip comment lines (starting with #)
            if (isset($data[0]) && strpos(trim($data[0]), '#') === 0) {
                continue;
            }

            // Check if we have enough columns (need at least 6 columns: ID, Nama, Kategori, Stok, Stok Tersedia, Status)
            if (count($data) < 6) {
                $errors[] = "Baris " . ($data[0] ?? 'unknown') . ": Kolom tidak cukup (minimal 6 kolom)";
                continue;
            }

            // Export format: ID, Nama Alat, Kategori, Stok Total, Stok Tersedia, Status, Deskripsi
            // We skip ID (auto-increment) and use the rest

            // Find category by name
            $category = Category::where('nama_kategori', $data[2])->first();

            if (!$category) {
                $errors[] = "Baris {$data[0]}: Kategori '{$data[2]}' tidak ditemukan";
                continue;
            }

            // Validate data
            $validator = Validator::make([
                'nama_alat' => $data[1] ?? null,
                'stok' => $data[3] ?? null,
                'status' => isset($data[5]) ? strtolower($data[5]) : null,
            ], [
                'nama_alat' => 'required|string|max:255',
                'stok' => 'required|integer|min:0',
                'status' => 'required|in:tersedia,dipinjam,rusak',
            ], [
                'nama_alat.required' => 'Nama alat wajib diisi',
                'nama_alat.max' => 'Nama alat maksimal 255 karakter',
                'stok.required' => 'Stok wajib diisi',
                'stok.integer' => 'Stok harus berupa angka',
                'stok.min' => 'Stok minimal 0',
                'status.required' => 'Status wajib diisi',
                'status.in' => 'Status tidak valid (pilihan: tersedia, dipinjam, rusak)',
            ]);

            if ($validator->fails()) {
                $errors[] = "Baris {$data[0]}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Create tool
            Tool::create([
                'nama_alat' => $data[1],
                'kategori_id' => $category->id,
                'stok' => $data[3],
                'stok_tersedia' => $data[4] ?? $data[3],
                'status' => strtolower($data[5]),
                'deskripsi' => $data[6] ?? null,
            ]);

            $imported++;
        }

        fclose($handle);

        // Provide detailed feedback
        if ($imported === 0 && count($errors) > 0) {
            // No data imported and there were errors
            return redirect()->back()
                ->with('error', 'Import gagal! Tidak ada data yang berhasil diimpor.')
                ->with('warning', 'Detail error: ' . implode(' | ', $errors));
        } elseif ($imported === 0 && count($errors) === 0) {
            // No data imported and no errors (probably empty file or all rows skipped)
            return redirect()->back()
                ->with('warning', 'Tidak ada data yang diimpor. File mungkin kosong atau semua baris dilewati.');
        } elseif ($imported > 0 && count($errors) > 0) {
            // Some data imported but there were errors
            return redirect()->back()
                ->with('success', "$imported alat berhasil diimpor.")
                ->with('warning', 'Beberapa baris gagal: ' . implode(' | ', array_slice($errors, 0, 5)));
        } else {
            // All data imported successfully
            return redirect()->back()->with('success', "$imported alat berhasil diimpor.");
        }
    }

    /**
     * Download template CSV
     */
    public function template()
    {
        $filename = 'template_tools.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // Get existing categories from database
        $categories = Category::limit(2)->get();

        $callback = function () use ($categories) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Add separator hint for Excel
            fwrite($file, "sep=,\n");

            // Header - same as export format
            fputcsv($file, ['ID', 'Nama Alat', 'Kategori', 'Stok Total', 'Stok Tersedia', 'Status', 'Deskripsi']);

            // Example data using actual categories from database
            if ($categories->count() > 0) {
                $cat1 = $categories->first()->nama_kategori;
                $cat2 = $categories->count() > 1 ? $categories->get(1)->nama_kategori : $cat1;

                fputcsv($file, ['1', 'Contoh Alat 1', $cat1, '10', '10', 'Tersedia', '-']);
                fputcsv($file, ['2', 'Contoh Alat 2', $cat2, '5', '5', 'Tersedia', '-']);
            } else {
                // If no categories exist, add placeholder
                fputcsv($file, ['1', 'Contoh Alat 1', 'BUAT_KATEGORI_DULU', '10', '10', 'Tersedia', '-']);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
