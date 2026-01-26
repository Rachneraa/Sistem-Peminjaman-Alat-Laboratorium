<?php

namespace App\Helpers;

use Carbon\Carbon;

class DendaHelper
{
    /**
     * Hitung denda berdasarkan jatuh tempo
     */
    public static function hitungDenda($tanggal_kembali, $jatuh_tempo, $denda_per_hari = 5000)
    {
        $tanggal_kembali = Carbon::parse($tanggal_kembali)->startOfDay();
        $jatuh_tempo = Carbon::parse($jatuh_tempo)->startOfDay();

        // Jika tanggal kembali <= jatuh tempo, tidak ada denda
        if ($tanggal_kembali->lte($jatuh_tempo)) {
            return [
                'denda' => 0,
                'terlambat_hari' => 0,
            ];
        }

        // Hitung hari terlambat: selisih hari dari jatuh tempo ke tanggal kembali
        // diffInDays memberikan selisih absolut, karena kita sudah pastikan tanggal_kembali > jatuh_tempo
        // maka hasilnya akan positif
        $terlambat_hari = $jatuh_tempo->diffInDays($tanggal_kembali);
        
        $denda = $terlambat_hari * $denda_per_hari;

        return [
            'denda' => $denda,
            'terlambat_hari' => $terlambat_hari,
        ];
    }
}

