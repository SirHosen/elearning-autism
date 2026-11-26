<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_see_role_select_page(): void
    {
        $response = $this->get('/masuk');
        $response->assertStatus(200);
        $response->assertSee('Saya Siswa');
        $response->assertSee('Saya Guru');
        $response->assertSee('role-vec--student');
        $response->assertSee('role-vec--teacher');
        $response->assertDontSee('murid-cover.jpg');
        $response->assertDontSee('guru-cover.jpg');
    }

    public function test_student_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/masuk/siswa');
        $response->assertStatus(200);
        $response->assertSee('Masuk Siswa');
        $response->assertSee('Nama Pengguna Kamu');
        $response->assertSee('role-vec--student');
        $response->assertSee('login-card-topbar');
        $response->assertSee('login-back-btn');
        $response->assertSee('Masuk Murid');
        $response->assertSee('Kembali');
        $response->assertDontSee('login-nav-row');
        $response->assertDontSee('murid-cover.jpg');
    }

    public function test_teacher_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/masuk/guru');
        $response->assertStatus(200);
        $response->assertSee('Masuk Guru');
        $response->assertSee('role-vec--teacher');
        $response->assertSee('login-card-topbar');
        $response->assertSee('login-back-btn');
        $response->assertSee('Portal Guru');
        $response->assertSee('Kembali');
        $response->assertDontSee('login-nav-row');
        $response->assertDontSee('guru-cover.jpg');
    }

    public function test_student_can_login_with_valid_username_and_pin(): void
    {
        $student = User::factory()->create([
            'username' => 'budi',
            'password' => Hash::make('1234'),
            'role' => 'siswa',
            'is_active' => true,
        ]);

        $response = $this->post('/masuk/siswa', [
            'username' => 'budi',
            'pin' => '1234',
        ]);

        $response->assertRedirect('/beranda');
        $this->assertAuthenticatedAs($student);
    }

    public function test_student_cannot_login_with_invalid_pin(): void
    {
        User::factory()->create([
            'username' => 'budi',
            'password' => Hash::make('1234'),
            'role' => 'siswa',
            'is_active' => true,
        ]);

        $response = $this->from('/masuk/siswa')->post('/masuk/siswa', [
            'username' => 'budi',
            'pin' => '9999',
        ]);

        $response->assertRedirect('/masuk/siswa');
        $response->assertSessionHasErrors('auth');
        $this->assertGuest();
    }

    public function test_student_login_throttled_after_failed_attempts(): void
    {
        User::factory()->create([
            'username' => 'budi',
            'password' => Hash::make('1234'),
            'role' => 'siswa',
            'is_active' => true,
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/masuk/siswa', [
                'username' => 'budi',
                'pin' => '0000',
            ]);
        }

        $response = $this->post('/masuk/siswa', [
            'username' => 'budi',
            'pin' => '0000',
        ]);

        $response->assertSessionHasErrors('auth');
        $this->assertStringContainsString('Tunggu sebentar', session('errors')->first('auth'));
    }

    public function test_inactive_student_cannot_login(): void
    {
        User::factory()->create([
            'username' => 'budi_nonaktif',
            'password' => Hash::make('1234'),
            'role' => 'siswa',
            'is_active' => false,
        ]);

        $response = $this->post('/masuk/siswa', [
            'username' => 'budi_nonaktif',
            'pin' => '1234',
        ]);

        $response->assertSessionHasErrors('auth');
        $this->assertGuest();
    }

    public function test_teacher_can_login_with_valid_email_and_password(): void
    {
        $teacher = User::factory()->create([
            'email' => 'guru@slb.test',
            'password' => Hash::make('rahasia123'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $response = $this->post('/masuk/guru', [
            'email' => 'guru@slb.test',
            'password' => 'rahasia123',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticatedAs($teacher);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $student = User::factory()->create([
            'role' => 'siswa',
            'is_active' => true,
        ]);

        $response = $this->actingAs($student)->post('/keluar');

        $response->assertRedirect('/masuk');
        $this->assertGuest();
    }
}
