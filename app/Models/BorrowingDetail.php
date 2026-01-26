<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowingDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'borrowing_id',
        'tool_id',
        'jumlah',
    ];

    /**
     * Relasi many-to-one dengan borrowings
     */
    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    /**
     * Relasi many-to-one dengan tools
     */
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}





