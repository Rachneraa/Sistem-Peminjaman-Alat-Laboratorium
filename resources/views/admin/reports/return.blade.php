<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Pengembalian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Laporan Pengembalian Alat</h1>
    <p>Periode Input: {{ \Carbon\Carbon::parse($display_start_date)->format('d/m/Y') }} -
        {{ \Carbon\Carbon::parse($display_end_date)->format('d/m/Y') }}</p>
    <p>Urutan Data: {{ $orderDirection === 'desc' ? 'Mundur (terbaru ke terlama)' : 'Maju (terlama ke terbaru)' }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Tanggal Kembali</th>
                <th>Alat</th>
                <th>Denda Keterlambatan</th>
                <th>Denda Kerusakan</th>
                <th>Total Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($returns as $return)
                <tr>
                    <td>{{ $return->id }}</td>
                    <td>{{ $return->borrowing->user->name }}</td>
                    <td>{{ $return->tanggal_kembali->format('d/m/Y') }}</td>
                    <td>
                        @foreach($return->borrowing->borrowingDetails as $detail)
                            {{ $detail->tool->nama_alat }} ({{ $detail->jumlah }})<br>
                        @endforeach
                    </td>
                    <td>Rp {{ number_format($return->denda, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($return->denda_kerusakan ?? 0, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $totalDenda = $return->denda + ($return->denda_kerusakan ?? 0);
                        @endphp
                        <strong>Rp {{ number_format($totalDenda, 0, ',', '.') }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            @php
                $totalDendaKeterlambatan = $returns->sum('denda');
                $totalDendaKerusakan = $returns->sum('denda_kerusakan');
                $totalKeseluruhan = $totalDendaKeterlambatan + $totalDendaKerusakan;
            @endphp
            <tr style="border-top: 2px solid #999;">
                <td colspan="6" style="text-align: right; font-weight: bold;">Total Denda Keterlambatan:</td>
                <td style="font-weight: bold;">Rp {{ number_format($totalDendaKeterlambatan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: right; font-weight: bold;">Total Denda Kerusakan:</td>
                <td style="font-weight: bold;">Rp {{ number_format($totalDendaKerusakan, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 2px solid #333;">
                <td colspan="6" style="text-align: right; font-weight: bold; font-size: 14px;">TOTAL KESELURUHAN:</td>
                <td style="font-weight: bold; font-size: 14px;">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>
</body>

</html>