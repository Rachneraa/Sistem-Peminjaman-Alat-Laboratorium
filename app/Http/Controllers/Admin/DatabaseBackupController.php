<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class DatabaseBackupController extends Controller
{
    /**
     * Export full database (structure + data) dan simpan ke storage/database.
     */
    public function export(): RedirectResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $connection = config('database.connections.mysql');

        $dbHost = $connection['host'] ?? '127.0.0.1';
        if (is_array($dbHost)) {
            $dbHost = $dbHost[0] ?? '127.0.0.1';
        }
        $dbPort = (string) ($connection['port'] ?? '3306');
        $dbName = $connection['database'] ?? null;
        $dbUsername = $connection['username'] ?? null;
        $dbPassword = $connection['password'] ?? '';

        if (!$dbName || !$dbUsername) {
            return back()->with('error', 'Konfigurasi database tidak lengkap, export dibatalkan.');
        }

        $backupDir = storage_path('database');
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        $timestamp = now()->format('Ymd_His');
        $backupPath = $backupDir . DIRECTORY_SEPARATOR . 'database_backup_' . $timestamp . '.sql';

        $dumpResult = $this->runDumpWithFallback((string) $dbHost, $dbPort, (string) $dbName, (string) $dbUsername, (string) $dbPassword);

        if ($dumpResult['success']) {
            File::put($backupPath, $dumpResult['output']);
        } else {
            Log::warning('mysqldump failed, switching to native SQL backup', [
                'message' => $dumpResult['message'],
                'db_host' => $dbHost,
                'db_port' => $dbPort,
                'db_name' => $dbName,
            ]);

            try {
                $this->buildNativeSqlBackup((string) $dbName, $backupPath);
            } catch (\Throwable $e) {
                Log::error('Native SQL backup failed', [
                    'error' => $e->getMessage(),
                    'db_name' => $dbName,
                ]);

                return back()->with('error', 'Gagal export database. ' . $dumpResult['message']);
            }
        }

        ActivityLog::createLog(
            auth()->id(),
            'Export database',
            null,
            basename($backupPath)
        );

        return response()->download($backupPath);
    }

    /**
     * Jalankan mysqldump dengan beberapa fallback binary agar stabil di web server Windows.
     *
     * @return array{success: bool, output: string, message: string}
     */
    private function runDumpWithFallback(string $dbHost, string $dbPort, string $dbName, string $dbUsername, string $dbPassword): array
    {
        $binaryCandidates = $this->resolveMysqldumpCandidates();
        if (empty($binaryCandidates)) {
            return [
                'success' => false,
                'output' => '',
                'message' => 'Binary mysqldump tidak ditemukan di PATH atau folder Laragon.',
            ];
        }

        $errors = [];
        $timeout = 20;

        foreach ($binaryCandidates as $binary) {
            $process = new Process([
                $binary,
                '--protocol=TCP',
                "--host={$dbHost}",
                "--port={$dbPort}",
                "--user={$dbUsername}",
                '--single-transaction',
                '--skip-lock-tables',
                '--routines',
                '--triggers',
                '--events',
                '--databases',
                $dbName,
            ]);

            if ($dbPassword !== '') {
                $process->setEnv([
                    'MYSQL_PWD' => $dbPassword,
                ]);
            }

            $process->setTimeout($timeout);
            $process->setIdleTimeout($timeout);
            $process->run();

            if ($process->isSuccessful() && trim($process->getOutput()) !== '') {
                return [
                    'success' => true,
                    'output' => $process->getOutput(),
                    'message' => '',
                ];
            }

            $errors[] = basename($binary) . ': ' . trim($process->getErrorOutput() ?: 'unknown error');

            if ($dbHost === 'localhost') {
                $retryProcess = new Process([
                    $binary,
                    '--protocol=TCP',
                    '--host=127.0.0.1',
                    "--port={$dbPort}",
                    "--user={$dbUsername}",
                    '--single-transaction',
                    '--skip-lock-tables',
                    '--routines',
                    '--triggers',
                    '--events',
                    '--databases',
                    $dbName,
                ]);

                if ($dbPassword !== '') {
                    $retryProcess->setEnv([
                        'MYSQL_PWD' => $dbPassword,
                    ]);
                }

                $retryProcess->setTimeout($timeout);
                $retryProcess->setIdleTimeout($timeout);
                $retryProcess->run();

                if ($retryProcess->isSuccessful() && trim($retryProcess->getOutput()) !== '') {
                    return [
                        'success' => true,
                        'output' => $retryProcess->getOutput(),
                        'message' => '',
                    ];
                }

                $errors[] = basename($binary) . ' (127.0.0.1): ' . trim($retryProcess->getErrorOutput() ?: 'unknown error');
            }
        }

        return [
            'success' => false,
            'output' => '',
            'message' => 'Pastikan mysqldump tersedia. Detail: ' . implode(' | ', $errors),
        ];
    }

    /**
     * Cari kandidat binary mysqldump dari PATH atau lokasi default Laragon di Windows.
     */
    private function resolveMysqldumpCandidates(): array
    {
        $candidates = [];

        if (PHP_OS_FAMILY === 'Windows') {
            $windowsPath = getenv('PATH') ?: '';
            foreach (explode(PATH_SEPARATOR, $windowsPath) as $dir) {
                $candidate = rtrim($dir, "\\/") . DIRECTORY_SEPARATOR . 'mysqldump.exe';
                if (is_file($candidate)) {
                    $candidates[] = $candidate;
                }
            }

            $laragonBinaries = glob('C:/laragon/bin/mysql/*/bin/mysqldump.exe') ?: [];
            rsort($laragonBinaries);
            foreach ($laragonBinaries as $binary) {
                if (is_file($binary)) {
                    $candidates[] = $binary;
                }
            }
        } else {
            $candidates[] = 'mysqldump';
        }

        if (PHP_OS_FAMILY === 'Windows') {
            $candidates[] = 'mysqldump.exe';
            $candidates[] = 'mysqldump';
        }

        return array_values(array_unique($candidates));
    }

    /**
     * Build SQL backup menggunakan koneksi Laravel tanpa ketergantungan mysqldump.
     */
    private function buildNativeSqlBackup(string $dbName, string $backupPath): void
    {
        $pdo = DB::connection()->getPdo();
        $tablesRaw = DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
        $handle = fopen($backupPath, 'wb');

        if ($handle === false) {
            throw new \RuntimeException('Tidak dapat membuat file backup.');
        }

        try {
            fwrite($handle, "-- Native backup generated by application\n");
            fwrite($handle, "-- Database: {$dbName}\n");
            fwrite($handle, '-- Generated at: ' . now()->toDateTimeString() . "\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            foreach ($tablesRaw as $tableRow) {
                $rowArray = (array) $tableRow;
                $tableName = (string) reset($rowArray);
                $tableNameEscaped = str_replace('`', '``', $tableName);

                $createRow = DB::selectOne("SHOW CREATE TABLE `{$tableNameEscaped}`");
                if (!$createRow) {
                    continue;
                }

                $createArray = array_values((array) $createRow);
                $createSql = $createArray[1] ?? '';

                fwrite($handle, "--\n");
                fwrite($handle, "-- Table structure for table `{$tableNameEscaped}`\n");
                fwrite($handle, "--\n");
                fwrite($handle, "DROP TABLE IF EXISTS `{$tableNameEscaped}`;\n");
                fwrite($handle, $createSql . ";\n\n");

                $batch = [];
                $columns = null;

                foreach (DB::table($tableName)->cursor() as $row) {
                    $rowData = (array) $row;

                    if ($columns === null) {
                        $columns = array_keys($rowData);
                        fwrite($handle, "--\n");
                        fwrite($handle, "-- Dumping data for table `{$tableNameEscaped}`\n");
                        fwrite($handle, "--\n");
                    }

                    $formattedValues = [];
                    foreach ($columns as $column) {
                        $formattedValues[] = $this->formatSqlValue($pdo, $rowData[$column] ?? null);
                    }

                    $batch[] = '(' . implode(', ', $formattedValues) . ')';

                    if (count($batch) >= 200) {
                        $this->writeInsertBatch($handle, $tableNameEscaped, $columns, $batch);
                        $batch = [];
                    }
                }

                if ($columns !== null && !empty($batch)) {
                    $this->writeInsertBatch($handle, $tableNameEscaped, $columns, $batch);
                }

                fwrite($handle, "\n");
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
        } finally {
            fclose($handle);
        }
    }

    /**
     * Tulis batch INSERT ke handle file backup agar tetap hemat memori.
     *
     * @param resource $handle
     */
    private function writeInsertBatch($handle, string $tableNameEscaped, array $columns, array $batch): void
    {
        $columnList = implode(', ', array_map(static function ($column) {
            return '`' . str_replace('`', '``', (string) $column) . '`';
        }, $columns));

        fwrite($handle, 'INSERT INTO `' . $tableNameEscaped . '` (' . $columnList . ") VALUES\n");
        fwrite($handle, implode(",\n", $batch) . ";\n");
    }

    /**
     * Format nilai menjadi SQL literal aman.
     */
    private function formatSqlValue(\PDO $pdo, mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        return $pdo->quote((string) $value);
    }
}
