<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnModel extends Model
{
    use HasFactory;

    /**
     * Nama tabel
     */
    protected $table = 'returns';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'borrowing_id',
        'tanggal_kembali',
        'denda',
        'denda_keterlambatan_awal',
        'terlambat_hari',
        'denda_kerusakan',
        'denda_diabaikan',
        'alasan_abaikan_denda',
        'keterangan',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'tanggal_kembali' => 'date',
            'denda' => 'decimal:2',
            'denda_keterlambatan_awal' => 'decimal:2',
            'denda_kerusakan' => 'decimal:2',
            'denda_diabaikan' => 'boolean',
        ];
    }

    /**
     * Relasi many-to-one dengan borrowings
     */
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}

