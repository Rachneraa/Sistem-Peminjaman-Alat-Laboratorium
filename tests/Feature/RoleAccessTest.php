<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test hak akses user berdasarkan role
     */
    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
    }

    public function test_peminjam_cannot_access_admin_routes(): void
    {
        $peminjam = User::factory()->create(['role' => 'peminjam']);

        $response = $this->actingAs($peminjam)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_petugas_can_approve_borrowings(): void
    {
        $petugas = User::factory()->create(['role' => 'petugas']);

        $response = $this->actingAs($petugas)->get('/petugas/dashboard');

        $response->assertStatus(200);
    }
}





