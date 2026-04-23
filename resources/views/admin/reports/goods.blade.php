<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Barang</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 13px;
        }

        th, td {
            border: 1px solid #e5e7eb;
            padding: 12px 10px;
            text-align: left;
        }

        th {
            background-color: #f8fafc;
            color: #374151;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.05em;
        }

        h1 {
            text-align: center;
            color: #111827;
            font-size: 24px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-line {
            width: 50px;
            height: 3px;
            background-color: #4f46e5;
            margin: 0 auto 20px auto;
        }

        .info {
            margin-bottom: 20px;
            font-size: 13px;
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #f3f4f6;
        }

        .info-row {
            margin-bottom: 5px;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .kondisi-item {
            margin-bottom: 3px;
            font-size: 11px;
        }
        .kondisi-item:last-child {
            margin-bottom: 0;
        }
        .kondisi-baik { color: #059669; }
        .kondisi-perbaikan { color: #d97706; }
        .kondisi-rusak { color: #dc2626; }
    </style>
</head>

<body>
    <h1>Laporan Barang / Alat</h1>
    <div class="header-line"></div>

    <div class="info">
        <div class="info-row">
            <strong>Periode:</strong> {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} -
            {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
        </div>
        <div class="info-row">
            <strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 10%;" class="text-center">ID</th>
                <th style="width: 30%;">Nama Alat</th>
                <th style="width: 25%;">Kondisi</th>
                <th style="width: 15%;" class="text-center">Persediaan</th>
                <th style="width: 20%;" class="text-center">Banyak Peminjam</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tools as $tool)
                <tr>
                    <td class="text-center">{{ $tool['id'] }}</td>
                    <td>{{ $tool['nama_alat'] }}</td>
                    <td>
                        <div class="kondisi-item kondisi-baik">✓ {{ $tool['kondisi_baik'] }} Baik</div>
                        <div class="kondisi-item kondisi-perbaikan">⚙ {{ $tool['kondisi_perbaikan'] }} Perbaikan</div>
                        <div class="kondisi-item kondisi-rusak">✕ {{ $tool['kondisi_rusak'] }} Rusak</div>
                    </td>
                    <td class="text-center">{{ $tool['persediaan'] }}</td>
                    <td class="text-center">{{ $tool['banyak_peminjam'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #6b7280;">Tidak ada data untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>