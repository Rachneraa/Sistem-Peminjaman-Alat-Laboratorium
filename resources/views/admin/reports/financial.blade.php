<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
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

        .summary {
            margin-top: 30px;
            background-color: #f8fafc;
            padding: 20px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
        }

        .summary h3 {
            margin-top: 0;
            color: #111827;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
            margin-bottom: 15px;
        }
        
        .summary p {
            font-size: 13px;
            color: #4b5563;
        }

        .total-fine {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }

        td.text-right, th.text-right {
            text-align: right;
        }

        td.text-center, th.text-center {
            text-align: center;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }
    </style>
</head>

<body>
    <h1>Laporan Keuangan</h1>
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
                <th style="width: 25%;">Peminjam</th>
                <th style="width: 25%;">Tanggal Peminjaman - Kembali</th>
                <th style="width: 15%;" class="text-right">Denda Terlambat</th>
                <th style="width: 15%;" class="text-right">Denda Kerusakan</th>
                <th style="width: 20%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returns as $index => $return)
                @php $total = ($return->return?->denda ?? 0) + ($return->return?->denda_kerusakan ?? 0); @endphp
                <tr>
                    <td>{{ $return->user->name }}</td>
                    <td>{{ $return->tanggal_pinjam->format('d/m/Y') }} - {{ $return->return?->tanggal_kembali?->format('d/m/Y') ?? '-' }}</td>
                    <td class="text-right">Rp {{ number_format($return->return?->denda ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($return->return?->denda_kerusakan ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 20px; color: #6b7280;">Tidak ada data untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <h3>Ringkasan Keuangan</h3>
        <p style="margin: 10px 0;"><strong>Total Transaksi Pengembalian:</strong> {{ count($returns) }}</p>
        <p style="margin: 10px 0;"><strong>Total Denda Keterlambatan:</strong> Rp
            {{ number_format($returns->sum('denda'), 0, ',', '.') }}
        </p>
        <p style="margin: 10px 0;"><strong>Total Denda Kerusakan:</strong> Rp
            {{ number_format($returns->sum('denda_kerusakan'), 0, ',', '.') }}
        </p>
        <p style="margin: 10px 0; font-size: 16px;">
            <strong>Total Pemasukan:</strong>
            <span class="total-fine">Rp {{ number_format($totalFine, 0, ',', '.') }}</span>
        </p>
    </div>
</body>

</html>