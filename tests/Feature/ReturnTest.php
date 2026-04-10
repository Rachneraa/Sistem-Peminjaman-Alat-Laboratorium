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

    private function createReturnFixture(): array
    {
        $petugas = User::factory()->create(['role' => 'petugas']);
        $peminjam = User::factory()->create(['role' => 'peminjam']);
        $category = Category::factory()->create();
        $tool = Tool::factory()->create([
            'kategori_id' => $category->id,
            'stok' => 10,
            'denda_per_hari' => 5000,
        ]);

        $borrowing = Borrowing::factory()->create([
            'user_id' => $peminjam->id,
            'tanggal_pinjam' => now()->subDays(10),
            'tanggal_selesai' => now()->subDays(3),
            'status' => 'disetujui',
        ]);

        BorrowingDetail::factory()->create([
            'borrowing_id' => $borrowing->id,
            'tool_id' => $tool->id,
            'jumlah' => 2,
        ]);

        $tool->decrement('stok', 2);

        return compact('petugas', 'peminjam', 'category', 'tool', 'borrowing');
    }

    /**
     * Test pengembalian alat dengan denda
     */
    public function test_petugas_can_process_return_with_penalty(): void
    {
        $fixture = $this->createReturnFixture();
        $petugas = $fixture['petugas'];
        $borrowing = $fixture['borrowing'];

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

    /**
     * Test petugas bisa mengabaikan denda dengan alasan
     */
    public function test_petugas_can_waive_late_fee_with_reason(): void
    {
        $fixture = $this->createReturnFixture();
        $petugas = $fixture['petugas'];
        $borrowing = $fixture['borrowing'];

        $response = $this->actingAs($petugas)->post("/petugas/borrowings/{$borrowing->id}/return", [
            'tanggal_kembali' => now()->format('Y-m-d'),
            'abaikan_denda' => '1',
            'alasan_abaikan_denda' => 'Alat terlambat karena maintenance laboratorium.',
            'keterangan' => 'Denda keterlambatan diabaikan',
        ]);

        $response->assertRedirect();

        $return = ReturnModel::where('borrowing_id', $borrowing->id)->first();
        $this->assertNotNull($return);
        $this->assertEquals(0.0, (float) $return->denda);
        $this->assertTrue((bool) $return->denda_diabaikan);
        $this->assertNotEmpty($return->alasan_abaikan_denda);
        $this->assertGreaterThan(0, (float) $return->denda_keterlambatan_awal);
    }

    /**
     * Test alasan wajib saat denda diabaikan
     */
    public function test_waive_late_fee_requires_reason(): void
    {
        $fixture = $this->createReturnFixture();
        $petugas = $fixture['petugas'];
        $borrowing = $fixture['borrowing'];

        $response = $this->from("/petugas/borrowings/{$borrowing->id}")
            ->actingAs($petugas)
            ->post("/petugas/borrowings/{$borrowing->id}/return", [
                'tanggal_kembali' => now()->format('Y-m-d'),
                'abaikan_denda' => '1',
                'keterangan' => 'Menguji validasi alasan',
            ]);

        $response->assertRedirect("/petugas/borrowings/{$borrowing->id}");
        $response->assertSessionHasErrors(['alasan_abaikan_denda']);
        $this->assertDatabaseMissing('returns', [
            'borrowing_id' => $borrowing->id,
        ]);
    }
}





