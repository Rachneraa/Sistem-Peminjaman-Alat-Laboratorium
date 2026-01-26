<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'tipe',
        'judul',
        'pesan',
        'status_baca',
        'related_id',
        'related_type',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'status_baca' => 'boolean',
        ];
    }

    /**
     * Relasi many-to-one dengan users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope untuk notifikasi yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->where('status_baca', false);
    }

    /**
     * Scope untuk notifikasi yang sudah dibaca
     */
    public function scopeRead($query)
    {
        return $query->where('status_baca', true);
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update(['status_baca' => true]);
    }

    /**
     * Helper untuk membuat notifikasi
     */
    public static function createNotification($user_id, $tipe, $judul, $pesan, $related_id = null, $related_type = null)
    {
        return self::create([
            'user_id' => $user_id,
            'tipe' => $tipe,
            'judul' => $judul,
            'pesan' => $pesan,
            'status_baca' => false,
            'related_id' => $related_id,
            'related_type' => $related_type,
        ]);
    }
}





