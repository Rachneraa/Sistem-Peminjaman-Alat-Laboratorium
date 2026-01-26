<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php if($borrowing->status == 'dikembalikan'): ?>
            Bukti Pengembalian #<?php echo e($borrowing->id); ?>

        <?php elseif(auth()->user()->isPeminjam()): ?>
            Bukti Pengajuan Peminjaman #<?php echo e($borrowing->id); ?>

        <?php else: ?>
            Bukti Peminjaman #<?php echo e($borrowing->id); ?>

        <?php endif; ?>
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
            <?php if($borrowing->status == 'dikembalikan'): ?>
                BUKTI PENGEMBALIAN ALAT
            <?php elseif(auth()->user()->isPeminjam()): ?>
                BUKTI PENGAJUAN PEMINJAMAN
            <?php else: ?>
                BUKTI PEMINJAMAN ALAT
            <?php endif; ?>
        </h1>
        <p>SMKN 1 JENPO</p>
    </div>

    <div class="info-grid">
        <div class="info-column">
            <div class="info-group">
                <label>Nomor Peminjaman</label>
                <p>#<?php echo e($borrowing->id); ?></p>
            </div>
            <br>
            <div class="info-group">
                <label>Peminjam</label>
                <p><?php echo e($borrowing->user->name); ?></p>
                <p style="font-size: 12px; font-weight: normal;"><?php echo e($borrowing->user->email); ?></p>
            </div>
        </div>
        <div class="info-column" style="text-align: right;">
            <div class="info-group">
                <label>Tanggal Pinjam</label>
                <p><?php echo e($borrowing->tanggal_pinjam->format('d/m/Y')); ?></p>
            </div>
            <br>
            <div class="info-group">
                <label>Tanggal Selesai/Jatuh Tempo</label>
                <p><?php echo e($borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-')); ?></p>
            </div>
            <?php if($borrowing->status == 'dikembalikan' && $borrowing->return): ?>
            <br>
            <div class="info-group">
                <label>Tanggal Dikembalikan</label>
                <p><?php echo e($borrowing->return->tanggal_kembali->format('d/m/Y')); ?></p>
            </div>
            <?php endif; ?>
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
            <?php $__currentLoopData = $borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($index + 1); ?></td>
                    <td>
                        <?php echo e($detail->tool->nama_alat); ?>

                    </td>
                    <td><?php echo e($detail->tool->category->nama_kategori); ?></td>
                    <td style="text-align: center;"><?php echo e($detail->tool->jumlah); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="info-grid" style="margin-top: 20px;">
        <div class="info-column">
            <div class="info-group">
                <label>Status</label>
                <span class="status-badge">
                    <?php if($borrowing->status == 'disetujui'): ?>
                        <?php if(auth()->user()->isPeminjam()): ?>
                            Disetujui (Belum Dipinjamkan)
                        <?php else: ?>
                            Disetujui (Dipinjamkan)
                        <?php endif; ?>
                    <?php else: ?>
                        <?php echo e(ucfirst(str_replace('_', ' ', $borrowing->status))); ?>

                    <?php endif; ?>
                </span>
            </div>
            <?php if($borrowing->status == 'dikembalikan' && $borrowing->return): ?>
                <br>
                <div class="info-group">
                    <label>Total Denda (Lunas)</label>
                    <p style="font-size: 18px; color: #333;">Rp <?php echo e(number_format($borrowing->return->denda + ($borrowing->return->denda_kerusakan ?? 0), 0, ',', '.')); ?></p>
                    <?php if($borrowing->return->denda > 0 || ($borrowing->return->denda_kerusakan ?? 0) > 0): ?>
                        <p style="font-size: 11px; font-weight: normal; color: #666;">
                            (Keterlambatan: <?php echo e(number_format($borrowing->return->denda)); ?> + Kerusakan: <?php echo e(number_format($borrowing->return->denda_kerusakan ?? 0)); ?>)
                        </p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php if($borrowing->keterangan || ($borrowing->return && $borrowing->return->keterangan)): ?>
        <div class="info-column" style="text-align: right;">
            <div class="info-group">
                <label>Keterangan</label>
                <p><?php echo e($borrowing->keterangan); ?></p>
                <?php if($borrowing->return && $borrowing->return->keterangan): ?>
                    <p style="font-size: 12px; margin-top: 5px;"><i>Catatan Pengembalian: <?php echo e($borrowing->return->keterangan); ?></i></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <?php if(auth()->user()->isPeminjam() && $borrowing->status != 'dikembalikan'): ?>
        <div style="margin-top: 30px; border: 1px dashed #666; padding: 10px; font-size: 12px; text-align: center; color: #444;">
            <strong>Catatan:</strong> Harap tunjukkan bukti ini kepada petugas untuk pengambilan alat.
        </div>
    <?php elseif(!auth()->user()->isPeminjam() && $borrowing->status != 'dikembalikan'): ?>
        <div style="margin-top: 30px; font-size: 12px; text-align: center; color: #666;">
            <i>Barang diterima dalam kondisi baik dan lengkap.</i>
        </div>
    <?php endif; ?>

    <div class="info-grid" style="margin-top: 50px;">
        <div class="info-column" style="text-align: center;">
            <p style="margin-bottom: 60px;">
                <?php if($borrowing->status == 'dikembalikan'): ?>
                    Yang Menerima Kembali,
                <?php elseif(auth()->user()->isPeminjam()): ?>
                    Petugas,
                <?php else: ?>
                    Yang Menyerahkan,
                <?php endif; ?>
            </p>
            <p>( ................................................. )</p>
        </div>
        <div class="info-column" style="text-align: center;">
            <p style="margin-bottom: 60px;">
                <?php if($borrowing->status == 'dikembalikan'): ?>
                    Yang Mengembalikan,
                <?php elseif(auth()->user()->isPeminjam()): ?>
                    Pemohon,
                <?php else: ?>
                    Yang Menerima,
                <?php endif; ?>
            </p>
            <p>( <?php echo e($borrowing->status == 'dikembalikan' ? $borrowing->user->name : (auth()->user()->isPeminjam() ? auth()->user()->name : $borrowing->user->name)); ?> )</p>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem pada <?php echo e(now()->format('d/m/Y H:i')); ?></p>
    </div>
</body>
</html>
<?php /**PATH D:\UKKfix\resources\views/borrowings/print.blade.php ENDPATH**/ ?>