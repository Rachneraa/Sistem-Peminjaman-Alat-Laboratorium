<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id',
        'tool_id',
        'jumlah_kembali',
        'jumlah_rusak',
        'persen_kerusakan',
        'denda_kerusakan_item',
    ];

    protected $casts = [
        'persen_kerusakan' => 'decimal:2',
        'denda_kerusakan_item' => 'decimal:2',
    ];

    public function return()
    {
        return $this->belongsTo(ReturnModel::class, 'return_id');
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}
