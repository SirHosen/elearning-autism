<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Game;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\User;
use App\Models\Video;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CurriculumAndCrudFixesTest extends TestCase
{
    use RefreshDatabase;

    public function test_unchecking_is_active_deactivates_categories_materials_quizzes_and_games(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();

        $category = Category::create([
            'name' => 'Musik',
            'slug' => 'musik',
            'color_key' => 'lavender',
            'is_active' => true,
        ]);

        $m1 = Material::create([
            'category_id' => $category->id,
            'label' => 'Gitar',
            'image_path' => 'materials/gitar.svg',
            'is_active' => true,
        ]);
        $m2 = Material::create([
            'category_id' => $category->id,
            'label' => 'Piano',
            'image_path' => 'materials/piano.svg',
            'is_active' => true,
        ]);

        $quiz = Quiz::create([
            'category_id' => $category->id,
            'title' => 'Kuis Musik',
            'is_active' => true,
        ]);

        $game = Game::create([
            'category_id' => $category->id,
            'title' => 'Tebak Alat Musik',
            'game_type' => 'pilih_benar',
            'option_count' => 2,
            'round_count' => 2,
            'instruction_text' => 'Pilih alat musik yang tepat.',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->put(route('admin.materials.update', $m1), [
            'category_id' => $category->id,
            'label' => 'Gitar Akustik',
            'is_active' => '0',
        ])->assertRedirect(route('admin.materials.index'));
        $this->assertFalse($m1->fresh()->is_active);

        $this->actingAs($admin)->put(route('admin.quizzes.update', $quiz), [
            'category_id' => $category->id,
            'title' => 'Kuis Musik',
            'is_active' => '0',
        ])->assertRedirect(route('admin.quizzes.index'));
        $this->assertFalse($quiz->fresh()->is_active);

        $this->actingAs($admin)->put(route('admin.games.update', $game), [
            'category_id' => $category->id,
            'title' => 'Tebak Alat Musik',
            'game_type' => 'pilih_benar',
            'option_count' => 2,
            'round_count' => 2,
            'is_active' => '0',
        ])->assertRedirect(route('admin.games.index'));
        $this->assertFalse($game->fresh()->is_active);

        $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'Musik',
            'slug' => 'musik',
            'color_key' => 'lavender',
            'is_active' => '0',
        ])->assertRedirect(route('admin.categories.index'));
        $this->assertFalse($category->fresh()->is_active);

        // Inactive category stays selectable on edit pages for admin
        $this->actingAs($admin)->get(route('admin.games.edit', $game))
            ->assertStatus(200)
            ->assertSee('Musik');
        $this->actingAs($admin)->get(route('admin.quizzes.edit', $quiz))
            ->assertStatus(200)
            ->assertSee('Musik');

        // Re-check material image update does not break when active=1
        $this->actingAs($admin)->put(route('admin.materials.update', $m2), [
            'category_id' => $category->id,
            'label' => 'Piano',
            'image' => UploadedFile::fake()->image('piano.png'),
            'is_active' => '1',
        ])->assertRedirect(route('admin.materials.index'));
        $this->assertTrue($m2->fresh()->is_active);
    }

    public function test_cannot_delete_category_with_videos_and_duplicate_slug_shows_validation_error(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::create([
            'name' => 'Bahasa Inggris',
            'slug' => 'bahasa-inggris',
            'color_key' => 'peach',
            'is_active' => true,
        ]);

        Video::create([
            'category_id' => $category->id,
            'title' => 'Belajar ABC',
            'source_type' => 'youtube',
            'youtube_id' => 'dQw4w9WgXcQ',
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->delete(route('admin.categories.destroy', $category))
            ->assertSessionHas('error');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);

        // Same name with empty slug should hit slug uniqueness validation instead of DB constraint 500
        $this->actingAs($admin)
            ->post(route('admin.categories.store'), [
                'name' => 'Bahasa Inggris',
                'slug' => '',
                'color_key' => 'peach',
            ])
            ->assertSessionHasErrors('slug');
    }

    public function test_database_seeder_populates_music_math_and_english_curricula_for_students(): void
    {
        $this->seed(DatabaseSeeder::class);

        $student = User::where('username', 'siswa_a')->firstOrFail();

        $this->assertDatabaseHas('categories', ['slug' => 'musik-nada', 'is_active' => true]);
        $this->assertDatabaseHas('categories', ['slug' => 'matematika', 'is_active' => true]);
        $this->assertDatabaseHas('categories', ['slug' => 'bahasa-inggris', 'is_active' => true]);

        $this->assertDatabaseHas('quizzes', ['title' => 'Kuis Mengenal Musik & Nada', 'is_active' => true]);
        $this->assertDatabaseHas('quizzes', ['title' => 'Kuis Matematika Dasar', 'is_active' => true]);
        $this->assertDatabaseHas('quizzes', ['title' => 'Kuis Kosakata Bahasa Inggris', 'is_active' => true]);

        $this->assertDatabaseHas('games', ['title' => 'Tebak Nada & Alat Musik', 'is_active' => true]);
        $this->assertDatabaseHas('games', ['title' => 'Hitung & Bentuk Ceria', 'is_active' => true]);
        $this->assertDatabaseHas('games', ['title' => 'Cocokkan Kosakata Bahasa Inggris', 'is_active' => true]);

        $this->actingAs($student)->get(route('student.learn.index'))
            ->assertStatus(200)
            ->assertSee('Musik &amp; Nada', false)
            ->assertSee('Matematika')
            ->assertSee('Bahasa Inggris');

        $this->actingAs($student)->get(route('student.quizzes.index'))
            ->assertStatus(200)
            ->assertSee('Kuis Matematika Dasar')
            ->assertSee('Kuis Kosakata Bahasa Inggris')
            ->assertSee('Kuis Mengenal Musik &amp; Nada', false);

        $this->actingAs($student)->get(route('student.games.index'))
            ->assertStatus(200)
            ->assertSee('st-hub-vec')
            ->assertSee('Tebak Nada &amp; Alat Musik', false)
            ->assertSee('Hitung &amp; Bentuk Ceria', false)
            ->assertSee('Cocokkan Kosakata Bahasa Inggris');

        $nadaDo = Material::where('label', 'Nada Do')->firstOrFail();
        $this->actingAs($student)->get(route('student.learn.material', ['musik-nada', $nadaDo->id]))
            ->assertStatus(200)
            ->assertSee('learn-scale-bar')
            ->assertSee('data-note-key="nada sol"', false);
    }
}
