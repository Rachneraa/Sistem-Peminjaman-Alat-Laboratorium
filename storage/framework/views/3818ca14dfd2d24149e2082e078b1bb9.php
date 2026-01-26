<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengembalian</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Laporan Pengembalian Alat</h1>
    <p>Periode: <?php echo e(\Carbon\Carbon::parse($start_date)->format('d/m/Y')); ?> - <?php echo e(\Carbon\Carbon::parse($end_date)->format('d/m/Y')); ?></p>
    
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
            <?php $__currentLoopData = $returns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $return): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($return->id); ?></td>
                    <td><?php echo e($return->borrowing->user->name); ?></td>
                    <td><?php echo e($return->tanggal_kembali->format('d/m/Y')); ?></td>
                    <td>
                        <?php $__currentLoopData = $return->borrowing->borrowingDetails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($detail->tool->nama_alat); ?> (<?php echo e($detail->jumlah); ?>)<br>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td>Rp <?php echo e(number_format($return->denda, 0, ',', '.')); ?></td>
                    <td>Rp <?php echo e(number_format($return->denda_kerusakan ?? 0, 0, ',', '.')); ?></td>
                    <td>
                        <?php
                            $totalDenda = $return->denda + ($return->denda_kerusakan ?? 0);
                        ?>
                        <strong>Rp <?php echo e(number_format($totalDenda, 0, ',', '.')); ?></strong>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
        <tfoot>
            <?php
                $totalDendaKeterlambatan = $returns->sum('denda');
                $totalDendaKerusakan = $returns->sum('denda_kerusakan');
                $totalKeseluruhan = $totalDendaKeterlambatan + $totalDendaKerusakan;
            ?>
            <tr style="border-top: 2px solid #999;">
                <td colspan="6" style="text-align: right; font-weight: bold;">Total Denda Keterlambatan:</td>
                <td style="font-weight: bold;">Rp <?php echo e(number_format($totalDendaKeterlambatan, 0, ',', '.')); ?></td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: right; font-weight: bold;">Total Denda Kerusakan:</td>
                <td style="font-weight: bold;">Rp <?php echo e(number_format($totalDendaKerusakan, 0, ',', '.')); ?></td>
            </tr>
            <tr style="border-top: 2px solid #333;">
                <td colspan="6" style="text-align: right; font-weight: bold; font-size: 14px;">TOTAL KESELURUHAN:</td>
                <td style="font-weight: bold; font-size: 14px;">Rp <?php echo e(number_format($totalKeseluruhan, 0, ',', '.')); ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>





<?php /**PATH D:\UKKfix\resources\views/admin/reports/return.blade.php ENDPATH**/ ?>