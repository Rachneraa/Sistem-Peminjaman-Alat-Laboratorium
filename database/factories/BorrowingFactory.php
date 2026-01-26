<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class BorrowingFactory extends Factory
{
    protected $model = \App\Models\Borrowing::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tanggal_pinjam' => fake()->dateTimeBetween('-1 month', 'now'),
            'tanggal_kembali' => null,
            'status' => fake()->randomElement(['menunggu', 'disetujui', 'ditolak', 'dikembalikan']),
            'keterangan' => fake()->optional()->sentence(),
        ];
    }
}





