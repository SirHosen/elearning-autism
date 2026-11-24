<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminStudentTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_create_student_with_auto_generated_pin(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.students.store'), [
            'name' => 'Dimas Anggara',
            'username' => 'dimas',
            'class_name' => 'Kelas 1',
            'pin' => '', // Auto generate
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('generated_pin');

        $this->assertDatabaseHas('users', [
            'name' => 'Dimas Anggara',
            'username' => 'dimas',
            'role' => 'siswa',
        ]);

        $student = User::where('username', 'dimas')->first();
        $this->assertNotNull($student->setting);
    }

    public function test_admin_can_reset_student_pin(): void
    {
        $student = User::factory()->create([
            'username' => 'budi',
            'password' => Hash::make('1111'),
            'role' => 'siswa',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.students.reset-pin', $student));

        $response->assertRedirect(route('admin.students.index'));
        $response->assertSessionHas('generated_pin');

        $newPin = session('generated_pin');
        $student->refresh();
        $this->assertTrue(Hash::check($newPin, $student->password));
    }

    public function test_admin_can_update_student_details(): void
    {
        $student = User::factory()->create([
            'name' => 'Siswa Lama',
            'username' => 'siswalama',
            'role' => 'siswa',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->put(route('admin.students.update', $student), [
            'name' => 'Siswa Baru',
            'username' => 'siswabaru',
            'class_name' => 'Kelas 2',
            'is_active' => '0',
        ]);

        $response->assertRedirect(route('admin.students.index'));
        $this->assertDatabaseHas('users', [
            'id' => $student->id,
            'name' => 'Siswa Baru',
            'username' => 'siswabaru',
            'is_active' => false,
        ]);
    }
}
