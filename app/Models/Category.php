<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'nama_kategori',
    ];

    /**
     * Relasi one-to-many dengan tools
     */
    public function tools()
    {
        return $this->hasMany(Tool::class, 'kategori_id');
    }
}





