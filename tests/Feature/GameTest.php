<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Game;
use App\Models\Material;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $student;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@slb.test',
        ]);

        $this->student = User::factory()->create([
            'role' => 'siswa',
            'username' => 'siswa_test',
        ]);

        $this->category = Category::create([
            'name' => 'Hewan',
            'slug' => 'hewan',
            'color' => '#5A8DEE',
            'is_active' => true,
        ]);

        // Create 5 active materials for testing
        for ($i = 1; $i <= 5; $i++) {
            Material::create([
                'category_id' => $this->category->id,
                'label' => "Hewan {$i}",
                'image_path' => "materials/hewan_{$i}.svg",
                'is_active' => true,
            ]);
        }
    }

    public function test_student_can_see_active_games_list(): void
    {
        $activeGame = Game::create([
            'category_id' => $this->category->id,
            'title' => 'Tebak Hewan Aktif',
            'game_type' => 'pilih_benar',
            'option_count' => 3,
            'round_count' => 5,
            'instruction_text' => 'Pilih hewan yang benar',
            'is_active' => true,
        ]);

        $inactiveGame = Game::create([
            'category_id' => $this->category->id,
            'title' => 'Tebak Hewan Sembunyi',
            'game_type' => 'pilih_benar',
            'option_count' => 3,
            'round_count' => 5,
            'instruction_text' => 'Pilih hewan',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->student)->get(route('student.games.index'));

        $response->assertStatus(200);
        $response->assertSee('Tebak Hewan Aktif');
        $response->assertDontSee('Tebak Hewan Sembunyi');
    }

    public function test_student_can_open_active_game_arena(): void
    {
        $game = Game::create([
            'category_id' => $this->category->id,
            'title' => 'Permainan Cocokkan',
            'game_type' => 'cocokkan',
            'option_count' => 3,
            'round_count' => 3,
            'instruction_text' => 'Cocokkan gambar dengan namanya',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->student)->get(route('student.games.play', $game));

        $response->assertStatus(200);
        $response->assertSee('Permainan Cocokkan');
        $response->assertSee('id="game-arena"', false);
        $response->assertSee('id="game-score-val"', false);
        $response->assertSee('id="btn-toggle-sfx"', false);
        $response->assertSee('id="game-feedback"', false);
        $response->assertSee('id="game-progress-dots"', false);
        $response->assertSee('game-engine.js');
    }

    public function test_student_cannot_open_inactive_game(): void
    {
        $game = Game::create([
            'category_id' => $this->category->id,
            'title' => 'Game Nonaktif',
            'game_type' => 'pilih_benar',
            'option_count' => 3,
            'round_count' => 5,
            'instruction_text' => 'Pilih hewan',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->student)->get(route('student.games.play', $game));

        $response->assertStatus(404);
    }

    public function test_student_can_fetch_rounds_for_all_three_engines(): void
    {
        $types = ['pilih_benar', 'cocokkan', 'seret_tempatkan'];

        foreach ($types as $type) {
            $game = Game::create([
                'category_id' => $this->category->id,
                'title' => "Game {$type}",
                'game_type' => $type,
                'option_count' => 3,
                'round_count' => 3,
                'instruction_text' => "Instruksi {$type}",
                'is_active' => true,
            ]);

            $response = $this->actingAs($this->student)->getJson(route('student.games.rounds', $game));

            $response->assertStatus(200);
            $response->assertJsonStructure([
                'success',
                'rounds',
            ]);
            $this->assertCount(3, $response->json('rounds'));
        }
    }

    public function test_student_can_record_game_session_and_calculate_stars(): void
    {
        $game = Game::create([
            'category_id' => $this->category->id,
            'title' => 'Uji Sesi Game',
            'game_type' => 'pilih_benar',
            'option_count' => 3,
            'round_count' => 5,
            'instruction_text' => 'Pilih yang benar',
            'is_active' => true,
        ]);

        // Case 1: 100% -> 3 stars
        $res3 = $this->actingAs($this->student)->postJson(route('student.games.session', $game), [
            'total_rounds' => 5,
            'correct_first_try' => 5,
            'duration_seconds' => 45,
        ]);
        $res3->assertStatus(200);
        $res3->assertJson(['success' => true, 'stars_earned' => 3]);

        // Case 2: 80% (4/5) -> 2 stars
        $res2 = $this->actingAs($this->student)->postJson(route('student.games.session', $game), [
            'total_rounds' => 5,
            'correct_first_try' => 4,
            'duration_seconds' => 60,
        ]);
        $res2->assertStatus(200);
        $res2->assertJson(['success' => true, 'stars_earned' => 2]);

        // Case 3: 20% (1/5) -> always minimum 1 star (sensory-safe positive reinforcement)
        $res1 = $this->actingAs($this->student)->postJson(route('student.games.session', $game), [
            'total_rounds' => 5,
            'correct_first_try' => 1,
            'duration_seconds' => 90,
        ]);
        $res1->assertStatus(200);
        $res1->assertJson(['success' => true, 'stars_earned' => 1]);

        $this->assertDatabaseHas('game_sessions', [
            'user_id' => $this->student->id,
            'game_id' => $game->id,
            'stars_earned' => 1,
        ]);
    }

    public function test_admin_can_create_game_when_category_has_enough_materials(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.games.store'), [
            'title' => 'Permainan Baru Sukses',
            'category_id' => $this->category->id,
            'game_type' => 'pilih_benar',
            'option_count' => 3,
            'round_count' => 5,
            'instruction_text' => 'Tebak gambar',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.games.index'));
        $this->assertDatabaseHas('games', [
            'title' => 'Permainan Baru Sukses',
            'is_active' => true,
        ]);
    }

    public function test_admin_cannot_activate_game_when_category_lacks_materials(): void
    {
        $emptyCategory = Category::create([
            'name' => 'Kosong',
            'slug' => 'kosong',
            'color' => '#CCCCCC',
            'is_active' => true,
        ]);

        // Only 1 material in this category
        Material::create([
            'category_id' => $emptyCategory->id,
            'label' => 'Satu Saja',
            'image_path' => 'materials/satu.svg',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.games.store'), [
            'title' => 'Permainan Harus Gagal',
            'category_id' => $emptyCategory->id,
            'game_type' => 'pilih_benar',
            'option_count' => 3, // Requires at least 3 materials
            'round_count' => 5,
            'is_active' => 1,
        ]);

        $response->assertSessionHasErrors('is_active');
        $this->assertDatabaseMissing('games', [
            'title' => 'Permainan Harus Gagal',
        ]);
    }

    public function test_admin_cannot_toggle_game_active_when_category_lacks_materials(): void
    {
        $emptyCategory = Category::create([
            'name' => 'Hampir Kosong',
            'slug' => 'hampir-kosong',
            'color' => '#CCCCCC',
            'is_active' => true,
        ]);

        $game = Game::create([
            'category_id' => $emptyCategory->id,
            'title' => 'Game Nonaktif Kosong',
            'game_type' => 'pilih_benar',
            'option_count' => 3,
            'round_count' => 5,
            'instruction_text' => 'Tebak',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.games.toggle', $game));
        $response->assertSessionHas('error');
        $this->assertFalse($game->fresh()->is_active);
    }

    public function test_admin_can_update_and_delete_game(): void
    {
        $game = Game::create([
            'category_id' => $this->category->id,
            'title' => 'Game Lama',
            'game_type' => 'pilih_benar',
            'option_count' => 3,
            'round_count' => 5,
            'instruction_text' => 'Instruksi',
            'is_active' => true,
        ]);

        // Update
        $updateRes = $this->actingAs($this->admin)->put(route('admin.games.update', $game), [
            'title' => 'Game Diperbarui',
            'category_id' => $this->category->id,
            'game_type' => 'pilih_benar',
            'option_count' => 3,
            'round_count' => 5,
            'is_active' => 1,
        ]);
        $updateRes->assertRedirect(route('admin.games.index'));
        $this->assertEquals('Game Diperbarui', $game->fresh()->title);

        // Delete
        $delRes = $this->actingAs($this->admin)->delete(route('admin.games.destroy', $game));
        $delRes->assertRedirect(route('admin.games.index'));
        $this->assertDatabaseMissing('games', ['id' => $game->id]);
    }

    public function test_game_api_guards_against_empty_category_inactive_game_and_score_overflow(): void
    {
        $emptyCat = Category::create([
            'name' => 'Kosong Aman',
            'slug' => 'kosong-aman',
            'color_key' => 'blue',
            'is_active' => true,
        ]);
        $emptyGame = Game::create([
            'category_id' => $emptyCat->id,
            'title' => 'Game Tanpa Materi',
            'game_type' => 'pilih_benar',
            'option_count' => 3,
            'round_count' => 5,
            'instruction_text' => 'Pilih gambar',
            'is_active' => true,
        ]);

        $roundsRes = $this->actingAs($this->student)->getJson(route('student.games.rounds', $emptyGame));
        $roundsRes->assertStatus(200)->assertJson(['success' => true, 'rounds' => []]);

        // Valid active game uses relative /storage/ asset paths
        $activeGame = Game::create([
            'category_id' => $this->category->id,
            'title' => 'Game Aktif Relatif',
            'game_type' => 'pilih_benar',
            'option_count' => 3,
            'round_count' => 2,
            'instruction_text' => 'Pilih gambar',
            'is_active' => true,
        ]);
        $activeRounds = $this->actingAs($this->student)->getJson(route('student.games.rounds', $activeGame));
        $activeRounds->assertStatus(200);
        $this->assertStringStartsWith('/storage/', $activeRounds->json('rounds.0.options.0.image_url'));

        // Score exceeding rounds is rejected (422)
        $this->actingAs($this->student)->postJson(route('student.games.session', $activeGame), [
            'total_rounds' => 3,
            'correct_first_try' => 5,
        ])->assertStatus(422);

        // Inactive game session storage is rejected (404)
        $activeGame->update(['is_active' => false]);
        $this->actingAs($this->student)->postJson(route('student.games.session', $activeGame), [
            'total_rounds' => 3,
            'correct_first_try' => 2,
        ])->assertStatus(404);
    }
}
