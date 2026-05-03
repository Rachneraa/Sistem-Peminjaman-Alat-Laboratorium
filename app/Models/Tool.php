<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama_alat',
        'kategori_id',
        'stok',
        'stok_rusak',
        'stok_perbaikan',
        'denda_per_hari',
        'harga_asli',
        'kondisi',
        'status',
        'deskripsi',
        'gambar',
    ];

    /**
     * Attribute casting
     */
    protected function casts(): array
    {
        return [
            'denda_per_hari' => 'decimal:2',
            'harga_asli' => 'decimal:2',
        ];
    }

    /**
     * Relasi many-to-one dengan categories
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    /**
     * Relasi many-to-many dengan borrowings melalui borrowing_details
     */
    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class);
    }

    /**
     * Cek apakah stok tersedia
     */
    public function isAvailable(int $jumlah = 1): bool
    {
        return $this->status === 'tersedia' && $this->stok >= $jumlah;
    }

    /**
     * Cek apakah bisa dipinjam
     */
    public function canBeBorrowed(): bool
    {
        return $this->status === 'tersedia' && $this->stok > 0;
    }

    /**
     * Update status berdasarkan stok
     */
    public function updateStatusFromStock()
    {
        if ($this->stok > 0 && in_array($this->status, ['tersedia', 'dipinjam'])) {
            $this->status = 'tersedia';
        } elseif ($this->stok === 0 && $this->status === 'tersedia') {
            $this->status = 'dipinjam';
        }
        $this->save();
    }

    /**
     * Hitung stok tersedia (stok total dikurangi yang sedang dipinjam)
     * Method ini menghitung stok tersedia dengan mengurangi jumlah yang sedang dipinjam
     * dari stok total untuk memastikan akurasi
     */
    public function getStokTersediaAttribute(): int
    {
        // Stok di database sudah dikurangi saat peminjaman disetujui (di controller)
        // Jadi kita cukup mengembalikan nilai stok yang ada di database
        return $this->stok;
    }

    /**
     * Get total stok (bagus + rusak + perbaikan)
     */
    public function getStokTotalAttribute(): int
    {
        return $this->stok + $this->stok_rusak + $this->stok_perbaikan;
    }
}

