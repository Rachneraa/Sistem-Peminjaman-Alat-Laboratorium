<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\DendaHelper;

class Borrowing extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'tanggal_pinjam',
        'tanggal_selesai',
        'tanggal_kembali',
        'jatuh_tempo',
        'status',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_selesai' => 'date',
            'tanggal_kembali' => 'date',
            'jatuh_tempo' => 'date',
        ];
    }

    /**
     * Relasi one-to-many dengan notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'related_id')->where('related_type', self::class);
    }

    /**
     * Cek apakah terlambat
     */
    public function isOverdue(): bool
    {
        if (!$this->jatuh_tempo && !$this->tanggal_selesai) {
            return false;
        }
        $tanggalSelesai = $this->tanggal_selesai ?? $this->jatuh_tempo;
        return $this->status === 'disetujui' && now()->gt($tanggalSelesai);
    }

    /**
     * Hitung estimasi denda berdasarkan tanggal sekarang
     */
    public function calculateEstimatedFine($denda_per_hari = 5000): array
    {
        $tanggalSelesai = $this->tanggal_selesai ?? $this->jatuh_tempo;
        
        if (!$tanggalSelesai) {
            return [
                'denda' => 0,
                'terlambat_hari' => 0,
            ];
        }

        // Gunakan startOfDay() untuk memastikan perhitungan konsisten
        // Parse tanggal selesai sebagai Carbon instance untuk konsistensi
        $tanggalSekarang = \Carbon\Carbon::now()->startOfDay();
        $tanggalSelesaiCarbon = \Carbon\Carbon::parse($tanggalSelesai)->startOfDay();
        
        return DendaHelper::hitungDenda($tanggalSekarang, $tanggalSelesaiCarbon, $denda_per_hari);
    }

    /**
     * Relasi many-to-one dengan users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi one-to-many dengan borrowing_details
     */
    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    /**
     * Relasi one-to-one dengan returns
     */
    public function return()
    {
        return $this->hasOne(ReturnModel::class, 'borrowing_id');
    }
}

