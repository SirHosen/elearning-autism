<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_admin_can_view_category_list(): void
    {
        Category::factory()->create(['name' => 'Kategori Uji', 'slug' => 'kategori-uji']);

        $response = $this->actingAs($this->admin)->get(route('admin.categories.index'));

        $response->assertStatus(200);
        $response->assertSee('Kategori Uji');
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Transportasi',
            'slug' => 'transportasi',
            'color_key' => 'blue',
            'description' => 'Mengenal macam kendaraan',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Transportasi', 'slug' => 'transportasi']);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::factory()->create(['name' => 'Kategori Lama', 'slug' => 'kategori-lama']);

        $response = $this->actingAs($this->admin)->put(route('admin.categories.update', $category), [
            'name' => 'Kategori Baru',
            'slug' => 'kategori-baru',
            'color_key' => 'green',
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'Kategori Baru']);
    }

    public function test_cannot_delete_category_with_materials(): void
    {
        $category = Category::factory()->create(['name' => 'Kategori Terisi']);
        Material::create([
            'category_id' => $category->id,
            'label' => 'Materi Tes',
            'image_path' => 'materials/dummy.webp',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $category));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
