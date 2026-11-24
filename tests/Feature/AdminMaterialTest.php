<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminMaterialTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->admin = User::factory()->admin()->create();
        $this->category = Category::create([
            'name' => 'Hewan',
            'slug' => 'hewan',
            'color_key' => 'blue',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_material_with_image_upload(): void
    {
        $image = UploadedFile::fake()->image('kelinci.png', 800, 600);

        $response = $this->actingAs($this->admin)->post(route('admin.materials.store'), [
            'category_id' => $this->category->id,
            'label' => 'Kelinci',
            'short_sentence' => 'Ini kelinci lucu.',
            'image' => $image,
            'is_active' => '1',
        ]);

        $response->assertRedirect(route('admin.materials.index'));
        $this->assertDatabaseHas('materials', [
            'label' => 'Kelinci',
            'category_id' => $this->category->id,
        ]);

        $material = Material::where('label', 'Kelinci')->first();
        $this->assertNotNull($material);
        $this->assertStringEndsWith('.webp', $material->image_path);
        Storage::disk('public')->assertExists($material->image_path);
    }

    public function test_cannot_upload_fake_image_file(): void
    {
        $fakeFile = UploadedFile::fake()->create('script.php.jpg', 10, 'text/plain');

        $response = $this->actingAs($this->admin)->post(route('admin.materials.store'), [
            'category_id' => $this->category->id,
            'label' => 'Virus',
            'image' => $fakeFile,
        ]);

        $response->assertSessionHasErrors('image');
        $this->assertDatabaseMissing('materials', ['label' => 'Virus']);
    }

    public function test_cannot_delete_material_used_in_quiz(): void
    {
        $material = Material::create([
            'category_id' => $this->category->id,
            'label' => 'Kucing',
            'image_path' => 'materials/dummy.webp',
        ]);

        $quiz = Quiz::create(['title' => 'Kuis Hewan']);
        QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'correct_material_id' => $material->id,
            'prompt_text' => 'Mana kucing?',
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.materials.destroy', $material));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('materials', ['id' => $material->id]);
    }

    public function test_admin_can_delete_free_material_and_cleans_file(): void
    {
        $image = UploadedFile::fake()->image('burung.png');
        $this->actingAs($this->admin)->post(route('admin.materials.store'), [
            'category_id' => $this->category->id,
            'label' => 'Burung',
            'image' => $image,
        ]);

        $material = Material::where('label', 'Burung')->first();
        $storedPath = $material->image_path;
        Storage::disk('public')->assertExists($storedPath);

        $response = $this->actingAs($this->admin)->delete(route('admin.materials.destroy', $material));

        $response->assertRedirect(route('admin.materials.index'));
        $this->assertDatabaseMissing('materials', ['id' => $material->id]);
        Storage::disk('public')->assertMissing($storedPath);
    }
}
