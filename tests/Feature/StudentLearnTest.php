<?php

namespace Tests\Feature;

use App\Models\Attendance;
use App\Models\Category;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentLearnTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;

    protected Category $category;

    protected Material $material1;

    protected Material $material2;

    protected function setUp(): void
    {
        parent::setUp();
        $this->student = User::factory()->create([
            'role' => 'siswa',
            'is_active' => true,
        ]);
        $this->student->setting()->create([
            'volume' => 70,
            'animation_level' => 'minimal',
            'font_scale' => 'normal',
            'theme' => 'calm',
        ]);

        $this->category = Category::create([
            'name' => 'Hewan',
            'slug' => 'hewan',
            'color_key' => 'blue',
            'is_active' => true,
        ]);

        $this->material1 = Material::create([
            'category_id' => $this->category->id,
            'label' => 'Kucing',
            'short_sentence' => 'Ini kucing lucu.',
            'image_path' => 'materials/dummy1.webp',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->material2 = Material::create([
            'category_id' => $this->category->id,
            'label' => 'Anjing',
            'short_sentence' => 'Ini anjing setia.',
            'image_path' => 'materials/dummy2.webp',
            'sort_order' => 2,
            'is_active' => true,
        ]);
    }

    public function test_student_can_view_home_dashboard(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.home'));

        $response->assertStatus(200);
        $response->assertSee('BELAJAR');
        $response->assertSee('BERMAIN');
        $response->assertSee('KUIS');
        $response->assertSee('VIDEO');
        $response->assertSee('ABSEN');
    }

    public function test_student_home_shows_attended_badge_if_present_today(): void
    {
        Attendance::create([
            'user_id' => $this->student->id,
            'attendance_date' => now()->format('Y-m-d'),
            'status' => 'hadir',
            'source' => 'siswa',
        ]);

        $response = $this->actingAs($this->student)->get(route('student.home'));

        $response->assertStatus(200);
        $response->assertSee('Sudah Hadir');
    }

    public function test_student_can_view_active_categories_list(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.learn.index'));

        $response->assertStatus(200);
        $response->assertSee('Hewan');
        $response->assertSee('2 materi tersedia');
    }

    public function test_student_can_view_material_in_viewer(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.learn.material', [
            'category' => $this->category->slug,
            'material' => $this->material1->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Kucing');
        $response->assertSee('Ini kucing lucu.');
        $response->assertSee('Dengar');
        $response->assertSee('1 dari 2');
    }

    public function test_viewer_shows_finish_button_at_last_material(): void
    {
        $response = $this->actingAs($this->student)->get(route('student.learn.material', [
            'category' => $this->category->slug,
            'material' => $this->material2->id,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Anjing');
        $response->assertSee('Selesai');
        $response->assertSee('2 dari 2');
    }

    public function test_inactive_category_returns_404(): void
    {
        $inactiveCat = Category::create([
            'name' => 'Angka',
            'slug' => 'angka',
            'color_key' => 'peach',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->student)->get(route('student.learn.category', $inactiveCat->slug));
        $response->assertStatus(404);
    }
}
