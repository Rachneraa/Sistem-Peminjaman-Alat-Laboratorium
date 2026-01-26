<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Peminjaman Alat</h1>
    <p>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</p>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Peminjam</th>
                <th>Tanggal Pinjam</th>
                <th>Alat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $borrowing)
                <tr>
                    <td>{{ $borrowing->id }}</td>
                    <td>{{ $borrowing->user->name }}</td>
                    <td>{{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td>
                        @foreach($borrowing->borrowingDetails as $detail)
                            {{ $detail->tool->nama_alat }} ({{ $detail->jumlah }})<br>
                        @endforeach
                    </td>
                    <td>{{ ucfirst($borrowing->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>





