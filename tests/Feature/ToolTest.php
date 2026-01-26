<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ToolTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    /**
     * Test tambah alat
     */
    public function test_admin_can_create_tool(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/tools', [
            'nama_alat' => 'Palu',
            'kategori_id' => $category->id,
            'stok' => 10,
            'kondisi' => 'baik',
            'deskripsi' => 'Palu untuk pertukangan',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tools', [
            'nama_alat' => 'Palu',
            'stok' => 10,
        ]);
    }

    /**
     * Test hanya admin yang bisa tambah alat
     */
    public function test_non_admin_cannot_create_tool(): void
    {
        $peminjam = User::factory()->create(['role' => 'peminjam']);
        $category = Category::factory()->create();

        $response = $this->actingAs($peminjam)->post('/admin/tools', [
            'nama_alat' => 'Palu',
            'kategori_id' => $category->id,
            'stok' => 10,
            'kondisi' => 'baik',
        ]);

        $response->assertStatus(403);
    }
}





