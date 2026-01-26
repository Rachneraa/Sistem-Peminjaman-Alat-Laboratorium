<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryExportImportController extends Controller
{
    /**
     * Export categories to CSV
     */
    public function export()
    {
        $categories = Category::all();
        
        $filename = 'categories_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function() use ($categories) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add separator hint for Excel
            fwrite($file, "sep=,\n");
            
            // Header row
            fputcsv($file, ['ID', 'Nama Kategori', 'Keterangan', 'Jumlah Alat']);
            
            // Data rows
            foreach ($categories as $category) {
                fputcsv($file, [
                    $category->id,
                    $category->nama_kategori,
                    $category->keterangan ?? '-',
                    $category->tools()->count()
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Import categories from CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);
        
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
        // If first line is not sep=, it's the header, so we're already past it
        
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
            
            // Check if we have enough columns (need at least 2 for content: ID, Nama Kategori)
            if (count($data) < 2) {
                $errors[] = "Baris " . ($data[0] ?? 'unknown') . ": Kolom tidak cukup (minimal 2 kolom)";
                continue;
            }
            
            // Export format: ID, Nama Kategori, Keterangan, Jumlah Alat
            // We skip ID (auto-increment) and Jumlah Alat (calculated field)
            
            // Validate data
            $validator = Validator::make([
                'nama_kategori' => $data[1] ?? null,
            ], [
                'nama_kategori' => 'required|string|max:255|unique:categories,nama_kategori',
            ], [
                'nama_kategori.required' => 'Nama kategori wajib diisi',
                'nama_kategori.max' => 'Nama kategori maksimal 255 karakter',
                'nama_kategori.unique' => 'Nama kategori sudah ada',
            ]);
            
            if ($validator->fails()) {
                $errors[] = "Baris {$data[0]}: " . implode(', ', $validator->errors()->all());
                continue;
            }
            
            // Create category
            Category::create([
                'nama_kategori' => $data[1],
                'keterangan' => $data[2] ?? null,
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
                ->with('success', "$imported kategori berhasil diimpor.")
                ->with('warning', 'Beberapa baris gagal: ' . implode(' | ', array_slice($errors, 0, 5)));
        } else {
            // All data imported successfully
            return redirect()->back()->with('success', "$imported kategori berhasil diimpor.");
        }
    }
    
    /**
     * Download template CSV
     */
    public function template()
    {
        $filename = 'template_categories.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        
        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Add separator hint for Excel
            fwrite($file, "sep=,\n");
            
            // Header - same as export format
            fputcsv($file, ['ID', 'Nama Kategori', 'Keterangan', 'Jumlah Alat']);
            
            // Example data - ID and Jumlah Alat will be ignored/auto-calculated during import
            fputcsv($file, ['1', 'Elektronik', 'Peralatan elektronik seperti laptop, proyektor, dll', '0']);
            fputcsv($file, ['2', 'Olahraga', 'Peralatan olahraga seperti bola, raket, dll', '0']);
            fputcsv($file, ['3', 'Laboratorium', 'Peralatan laboratorium untuk praktikum', '0']);
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
