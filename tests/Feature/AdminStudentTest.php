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

    public function test_admin_can_soft_delete_student_and_cannot_manage_admin_as_student(): void
    {
        $student = User::factory()->create([
            'name' => 'Siswa Arsip',
            'username' => 'siswa_arsip',
            'role' => 'siswa',
        ]);

        // Admin cannot edit, reset PIN, update attendance, or delete an admin account via student endpoints
        $this->actingAs($this->admin)->get(route('admin.students.edit', $this->admin))->assertStatus(404);
        $this->actingAs($this->admin)->post(route('admin.students.reset-pin', $this->admin))->assertStatus(404);
        $this->actingAs($this->admin)->delete(route('admin.students.destroy', $this->admin))->assertStatus(404);
        $this->actingAs($this->admin)->post(route('admin.attendances.update', $this->admin), [
            'date' => now()->toDateString(),
            'status' => 'hadir',
        ])->assertStatus(404);

        // Removing a student performs a safe soft-delete
        $deleteRes = $this->actingAs($this->admin)->delete(route('admin.students.destroy', $student));
        $deleteRes->assertRedirect(route('admin.students.index'));
        $this->assertSoftDeleted('users', ['id' => $student->id]);
    }

    public function test_unused_admin_resource_show_routes_return_405_not_500(): void
    {
        $this->actingAs($this->admin)->get('/admin/siswa/999')->assertStatus(405);
        $this->actingAs($this->admin)->get('/admin/kategori/999')->assertStatus(405);
        $this->actingAs($this->admin)->get('/admin/materi/999')->assertStatus(405);
    }
}
