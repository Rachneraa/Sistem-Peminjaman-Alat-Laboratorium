<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Borrowing;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test ajukan peminjaman
     */
    public function test_peminjam_can_request_borrowing(): void
    {
        $peminjam = User::factory()->create(['role' => 'peminjam']);
        $category = Category::factory()->create();
        $tool = Tool::factory()->create([
            'kategori_id' => $category->id,
            'stok' => 10,
        ]);

        $response = $this->actingAs($peminjam)->post('/peminjam/borrowings', [
            'tanggal_pinjam' => now()->format('Y-m-d'),
            'tools' => [
                [
                    'tool_id' => $tool->id,
                    'jumlah' => 2,
                ],
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('borrowings', [
            'user_id' => $peminjam->id,
            'status' => 'menunggu',
        ]);
    }
}





