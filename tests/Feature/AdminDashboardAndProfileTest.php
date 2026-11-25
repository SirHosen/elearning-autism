<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminDashboardAndProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'name' => 'Ibu Sari',
            'email' => 'sari@slb.test',
            'username' => 'ibu_sari',
            'password' => Hash::make('secret123'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_dashboard_renders_integrated_matrix_and_trend(): void
    {
        $category = Category::create([
            'name' => 'Hewan',
            'slug' => 'hewan',
            'color_key' => 'blue',
            'is_active' => true,
        ]);

        Material::create([
            'category_id' => $category->id,
            'label' => 'Kucing',
            'image_path' => 'materials/hewan-1.svg',
            'is_active' => true,
        ]);

        User::factory()->create([
            'name' => 'Budi Santoso',
            'username' => 'budi_s',
            'role' => 'siswa',
            'class_name' => 'Kelas 1',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Selamat Bekerja, Ibu Sari');
        $response->assertSee('Matriks Kesiapan Kurikulum per Kategori');
        $response->assertSee('Hewan');
        $response->assertSee('Budi Santoso');
    }

    public function test_admin_can_view_and_update_teacher_profile(): void
    {
        $viewRes = $this->actingAs($this->admin)->get(route('admin.profile'));
        $viewRes->assertStatus(200);
        $viewRes->assertSee('Profil &amp; Keamanan Akun Guru', false);
        $viewRes->assertSee('sari@slb.test');

        $updateRes = $this->actingAs($this->admin)->put(route('admin.profile.update'), [
            'name' => 'Ibu Sari Wijaya',
            'email' => 'sari.wijaya@slb.test',
            'current_password' => 'secret123',
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
        ]);

        $updateRes->assertRedirect(route('admin.profile'));
        $updateRes->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertSame('Ibu Sari Wijaya', $this->admin->name);
        $this->assertSame('sari.wijaya@slb.test', $this->admin->email);
        $this->assertTrue(Hash::check('newpass123', $this->admin->password));
    }

    public function test_admin_reports_index_displays_class_overview_without_selected_student(): void
    {
        User::factory()->create([
            'name' => 'Citra Kirana',
            'username' => 'citra_k',
            'role' => 'siswa',
            'class_name' => 'Kelas 2',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Rekap Evaluasi Seluruh Siswa');
        $response->assertSee('Citra Kirana');
    }

    public function test_all_admin_curriculum_pages_render_without_errors(): void
    {
        $routes = [
            'admin.dashboard',
            'admin.categories.index',
            'admin.materials.index',
            'admin.videos.index',
            'admin.videos.create',
            'admin.games.index',
            'admin.games.create',
            'admin.quizzes.index',
            'admin.quizzes.create',
            'admin.students.index',
            'admin.attendances.index',
            'admin.reports.index',
            'admin.profile',
        ];

        foreach ($routes as $name) {
            $res = $this->actingAs($this->admin)->get(route($name));
            $res->assertStatus(200);
        }
    }

    public function test_admin_pagination_uses_custom_styled_view_without_tailwind_icons(): void
    {
        $category = Category::create([
            'name' => 'Hewan',
            'slug' => 'hewan',
            'color_key' => 'blue',
            'is_active' => true,
        ]);

        for ($i = 1; $i <= 18; $i++) {
            Material::create([
                'category_id' => $category->id,
                'label' => "Materi {$i}",
                'image_path' => 'materials/hewan-1.svg',
                'is_active' => true,
            ]);
        }

        $res = $this->actingAs($this->admin)->get(route('admin.materials.index', ['page' => 1]));

        $res->assertStatus(200);
        $res->assertSee('adm-pagination');
        $res->assertSee('Berikutnya');
        $res->assertDontSee('pagination.previous');
        $res->assertDontSee('pagination.next');
        $res->assertDontSee('w-5 h-5');
    }
}
