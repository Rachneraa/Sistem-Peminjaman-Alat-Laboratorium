<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @if($borrowing->status == 'dikembalikan')
            Bukti Pengembalian #{{ $borrowing->id }}
        @elseif(auth()->user()->isPeminjam())
            Bukti Pengajuan Peminjaman #{{ $borrowing->id }}
        @else
            Bukti Peminjaman #{{ $borrowing->id }}
        @endif
    </title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background: #fff;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .info-group label {
            display: block;
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .info-group p {
            margin: 0;
            font-weight: bold;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 12px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>
            @if($borrowing->status == 'dikembalikan')
                BUKTI PENGEMBALIAN ALAT
            @elseif(auth()->user()->isPeminjam())
                BUKTI PENGAJUAN PEMINJAMAN
            @else
                BUKTI PEMINJAMAN ALAT
            @endif
        </h1>
        <p>SMKN 1 JENPO</p>
    </div>

    <div class="info-grid">
        <div class="info-column">
            <div class="info-group">
                <label>Nomor Peminjaman</label>
                <p>#{{ $borrowing->id }}</p>
            </div>
            <br>
            <div class="info-group">
                <label>Peminjam</label>
                <p>{{ $borrowing->user->name }}</p>
                <p style="font-size: 12px; font-weight: normal;">{{ $borrowing->user->email }}</p>
            </div>
        </div>
        <div class="info-column" style="text-align: right;">
            <div class="info-group">
                <label>Tanggal Pinjam</label>
                <p>{{ $borrowing->tanggal_pinjam->format('d/m/Y') }}</p>
            </div>
            <br>
            <div class="info-group">
                <label>Tanggal Selesai/Jatuh Tempo</label>
                <p>{{ $borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-') }}</p>
            </div>
            @if($borrowing->status == 'dikembalikan' && $borrowing->return)
            <br>
            <div class="info-group">
                <label>Tanggal Dikembalikan</label>
                <p>{{ $borrowing->return->tanggal_kembali->format('d/m/Y') }}</p>
            </div>
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Alat</th>
                <th>Kategori</th>
                <th style="text-align: center;">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowing->borrowingDetails as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $detail->tool->nama_alat }}
                    </td>
                    <td>{{ $detail->tool->category->nama_kategori }}</td>
                    <td style="text-align: center;">{{ $detail->tool->jumlah }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="info-grid" style="margin-top: 20px;">
        <div class="info-column">
            <div class="info-group">
                <label>Status</label>
                <span class="status-badge">
                    @if($borrowing->status == 'disetujui')
                        @if(auth()->user()->isPeminjam())
                            Disetujui (Belum Dipinjamkan)
                        @else
                            Disetujui (Dipinjamkan)
                        @endif
                    @else
                        {{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}
                    @endif
                </span>
            </div>
            @if($borrowing->status == 'dikembalikan' && $borrowing->return)
                <br>
                <div class="info-group">
                    <label>Total Denda (Lunas)</label>
                    <p style="font-size: 18px; color: #333;">Rp {{ number_format($borrowing->return->denda + ($borrowing->return->denda_kerusakan ?? 0), 0, ',', '.') }}</p>
                    @if($borrowing->return->denda > 0 || ($borrowing->return->denda_kerusakan ?? 0) > 0)
                        <p style="font-size: 11px; font-weight: normal; color: #666;">
                            (Keterlambatan: {{ number_format($borrowing->return->denda) }} + Kerusakan: {{ number_format($borrowing->return->denda_kerusakan ?? 0) }})
                        </p>
                    @endif
                </div>
            @endif
        </div>
        @if($borrowing->keterangan || ($borrowing->return && $borrowing->return->keterangan))
        <div class="info-column" style="text-align: right;">
            <div class="info-group">
                <label>Keterangan</label>
                <p>{{ $borrowing->keterangan }}</p>
                @if($borrowing->return && $borrowing->return->keterangan)
                    <p style="font-size: 12px; margin-top: 5px;"><i>Catatan Pengembalian: {{ $borrowing->return->keterangan }}</i></p>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Footer Notes --}}
    @if(auth()->user()->isPeminjam() && $borrowing->status != 'dikembalikan')
        <div style="margin-top: 30px; border: 1px dashed #666; padding: 10px; font-size: 12px; text-align: center; color: #444;">
            <strong>Catatan:</strong> Harap tunjukkan bukti ini kepada petugas untuk pengambilan alat.
        </div>
    @elseif(!auth()->user()->isPeminjam() && $borrowing->status != 'dikembalikan')
        <div style="margin-top: 30px; font-size: 12px; text-align: center; color: #666;">
            <i>Barang diterima dalam kondisi baik dan lengkap.</i>
        </div>
    @endif

    <div class="info-grid" style="margin-top: 50px;">
        <div class="info-column" style="text-align: center;">
            <p style="margin-bottom: 60px;">
                @if($borrowing->status == 'dikembalikan')
                    Yang Menerima Kembali,
                @elseif(auth()->user()->isPeminjam())
                    Petugas,
                @else
                    Yang Menyerahkan,
                @endif
            </p>
            <p>( ................................................. )</p>
        </div>
        <div class="info-column" style="text-align: center;">
            <p style="margin-bottom: 60px;">
                @if($borrowing->status == 'dikembalikan')
                    Yang Mengembalikan,
                @elseif(auth()->user()->isPeminjam())
                    Pemohon,
                @else
                    Yang Menerima,
                @endif
            </p>
            <p>( {{ $borrowing->status == 'dikembalikan' ? $borrowing->user->name : (auth()->user()->isPeminjam() ? auth()->user()->name : $borrowing->user->name) }} )</p>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem pada {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
