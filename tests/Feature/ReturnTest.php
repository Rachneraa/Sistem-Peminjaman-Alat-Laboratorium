<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use App\Models\ReturnModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReturnTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test pengembalian alat dengan denda
     */
    public function test_petugas_can_process_return_with_penalty(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $peminjam = User::factory()->create(['role' => 'peminjam']);
        $category = Category::factory()->create();
        $tool = Tool::factory()->create([
            'kategori_id' => $category->id,
            'stok' => 10,
        ]);

        $borrowing = Borrowing::factory()->create([
            'user_id' => $peminjam->id,
            'tanggal_pinjam' => now()->subDays(10),
            'status' => 'disetujui',
        ]);

        BorrowingDetail::factory()->create([
            'borrowing_id' => $borrowing->id,
            'tool_id' => $tool->id,
            'jumlah' => 2,
        ]);

        $tool->decrement('stok', 2);

        $response = $this->actingAs($petugas)->post("/petugas/borrowings/{$borrowing->id}/return", [
            'tanggal_kembali' => now()->format('Y-m-d'),
            'keterangan' => 'Alat dikembalikan',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('returns', [
            'borrowing_id' => $borrowing->id,
        ]);
        
        $return = ReturnModel::where('borrowing_id', $borrowing->id)->first();
        $this->assertGreaterThan(0, $return->denda);
    }
}





