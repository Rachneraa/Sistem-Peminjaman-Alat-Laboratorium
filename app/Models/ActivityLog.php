<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'aktivitas',
        'model_type',
        'model_id',
        'detail',
    ];

    /**
     * Relasi many-to-one dengan users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi polymorphic
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * Helper untuk membuat log aktivitas
     */
    public static function createLog($user_id, $aktivitas, $model = null, $detail = null)
    {
        return self::create([
            'user_id' => $user_id,
            'aktivitas' => $aktivitas,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'detail' => $detail,
        ]);
    }
}





