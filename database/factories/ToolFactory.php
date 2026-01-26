<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class ToolFactory extends Factory
{
    protected $model = \App\Models\Tool::class;

    public function definition(): array
    {
        return [
            'nama_alat' => fake()->words(2, true),
            'kategori_id' => Category::factory(),
            'stok' => fake()->numberBetween(0, 50),
            'kondisi' => fake()->randomElement(['baik', 'rusak', 'perlu_perbaikan']),
            'deskripsi' => fake()->sentence(),
        ];
    }
}





