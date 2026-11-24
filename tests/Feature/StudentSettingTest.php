<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentSettingTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();
        $this->student = User::factory()->create([
            'role' => 'siswa',
            'is_active' => true,
        ]);
    }

    public function test_student_can_view_settings_page(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.settings'));

        $response->assertStatus(200);
        $response->assertSee('Kekerasan Suara');
        $response->assertSee('Warna Tampilan Layar');
        $response->assertSee('Ukuran Huruf');
        $response->assertSee('Animasi Layar');
    }

    public function test_student_can_update_settings(): void
    {
        $response = $this->actingAs($this->student)->putJson(route('student.settings.update'), [
            'volume' => 90,
            'animation_level' => 'normal',
            'font_scale' => 'large',
            'theme' => 'contrast',
            'sfx_enabled' => true,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'settings' => [
                'volume' => 90,
                'animation_level' => 'normal',
                'font_scale' => 'large',
                'theme' => 'contrast',
                'sfx_enabled' => true,
            ],
        ]);

        $this->assertDatabaseHas('user_settings', [
            'user_id' => $this->student->id,
            'volume' => 90,
            'animation_level' => 'normal',
            'font_scale' => 'large',
            'theme' => 'contrast',
            'sfx_enabled' => 1,
        ]);
    }

    public function test_invalid_settings_are_rejected(): void
    {
        $response = $this->actingAs($this->student)->putJson(route('student.settings.update'), [
            'volume' => 150, // Max 100
            'animation_level' => 'ultra-fast',
            'font_scale' => 'tiny',
            'theme' => 'neon',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['volume', 'animation_level', 'font_scale', 'theme']);
    }
}
