<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_access_admin_dashboard_gets_403(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)->get('/admin');

        $response->assertStatus(403);
        $response->assertSee('Halaman Terkunci');
        $response->assertSee('Bapak/Ibu Guru');
    }

    public function test_guest_cannot_access_student_home_gets_redirected(): void
    {
        $response = $this->get('/beranda');

        $response->assertRedirect('/masuk');
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Panel Pengelolaan Guru');
    }
}
