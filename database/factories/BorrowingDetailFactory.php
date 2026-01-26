<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Borrowing;
use App\Models\Tool;

class BorrowingDetailFactory extends Factory
{
    protected $model = \App\Models\BorrowingDetail::class;

    public function definition(): array
    {
        return [
            'borrowing_id' => Borrowing::factory(),
            'tool_id' => Tool::factory(),
            'jumlah' => fake()->numberBetween(1, 5),
        ];
    }
}





