<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserExportImportController extends Controller
{
    /**
     * Export users to CSV
     */
    public function export()
    {
        $filename = 'users_' . date('Y-m-d_His') . '.csv';

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
            fputcsv($file, ['ID', 'Nama', 'Email', 'Role', 'Tanggal Dibuat']);

            // Stream rows per chunk to reduce memory usage.
            User::query()
                ->select(['id', 'name', 'email', 'role', 'created_at'])
                ->orderBy('id')
                ->chunkById(500, function ($users) use ($file) {
                    foreach ($users as $user) {
                        fputcsv($file, [
                            $user->id,
                            $user->name,
                            $user->email,
                            ucfirst($user->role),
                            $user->created_at?->format('d/m/Y H:i') ?? '-',
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import users from CSV
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

            // Check if we have enough columns (need at least 4 for content: ID, Name, Email, Role)
            if (count($data) < 4) {
                $errors[] = "Baris " . ($data[0] ?? 'unknown') . ": Kolom tidak cukup (minimal 4 kolom)";
                continue;
            }

            // Export format: ID, Nama, Email, Role, Tanggal Dibuat
            // We skip ID (auto-increment) and Tanggal Dibuat (auto-generated)

            // Validate data
            $validator = Validator::make([
                'name' => $data[1] ?? null,
                'email' => $data[2] ?? null,
                'role' => isset($data[3]) ? strtolower($data[3]) : null,
            ], [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:admin,petugas,peminjam',
            ], [
                'name.required' => 'Nama wajib diisi',
                'name.max' => 'Nama maksimal 255 karakter',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'role.required' => 'Role wajib diisi',
                'role.in' => 'Role tidak valid (pilihan: admin, petugas, peminjam)',
            ]);

            if ($validator->fails()) {
                $errors[] = "Baris {$data[0]}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            // Create user
            User::create([
                'name' => $data[1],
                'email' => $data[2],
                'role' => strtolower($data[3]),
                'password' => Hash::make('password'), // Default password
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
                ->with('success', "$imported user berhasil diimpor.")
                ->with('warning', 'Beberapa baris gagal: ' . implode(' | ', array_slice($errors, 0, 5)));
        } else {
            // All data imported successfully
            return redirect()->back()->with('success', "$imported user berhasil diimpor.");
        }
    }

    /**
     * Download template CSV
     */
    public function template()
    {
        $filename = 'template_users.csv';

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

            // Header - same as export format
            fputcsv($file, ['ID', 'Nama', 'Email', 'Role', 'Tanggal Dibuat']);

            // Example data - ID and Tanggal Dibuat will be ignored/auto-generated during import
            fputcsv($file, ['1', 'John Doe', 'john@example.com', 'Peminjam', date('d/m/Y H:i')]);
            fputcsv($file, ['2', 'Jane Smith', 'jane@example.com', 'Petugas', date('d/m/Y H:i')]);
            fputcsv($file, ['3', 'Admin User', 'admin@example.com', 'Admin', date('d/m/Y H:i')]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
