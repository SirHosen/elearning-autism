<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VideoTest extends TestCase
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
    }

    public function test_student_can_see_active_videos_list(): void
    {
        $activeVideo = Video::create([
            'category_id' => $this->category->id,
            'title' => 'Video Kucing Lucu',
            'source_type' => 'youtube',
            'youtube_id' => 'dQw4w9WgXcQ',
            'is_active' => true,
        ]);

        $inactiveVideo = Video::create([
            'category_id' => $this->category->id,
            'title' => 'Video Rahasia',
            'source_type' => 'youtube',
            'youtube_id' => 'dQw4w9WgXcQ',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->student)->get(route('student.videos.index'));

        $response->assertStatus(200);
        $response->assertSee('Video Kucing Lucu');
        $response->assertDontSee('Video Rahasia');
    }

    public function test_student_can_view_active_uploaded_video_without_autoplay(): void
    {
        $video = Video::create([
            'category_id' => $this->category->id,
            'title' => 'Video Sapi Berjalan',
            'source_type' => 'upload',
            'video_path' => 'videos/sample.mp4',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->student)->get(route('student.videos.show', $video));

        $response->assertStatus(200);
        $response->assertSee('Video Sapi Berjalan');
        $response->assertSee('<video id="edu-video-player" controls playsinline preload="metadata"', false);
        $response->assertDontSee('autoplay');
    }

    public function test_student_can_view_active_youtube_video_without_autoplay(): void
    {
        $video = Video::create([
            'category_id' => $this->category->id,
            'title' => 'Lagu Burung Kakatua',
            'source_type' => 'youtube',
            'youtube_id' => 'abc12345678',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->student)->get(route('student.videos.show', $video));

        $response->assertStatus(200);
        $response->assertSee('Lagu Burung Kakatua');
        $response->assertSee('https://www.youtube-nocookie.com/embed/abc12345678?rel=0&amp;modestbranding=1&amp;playsinline=1', false);
        $response->assertDontSee('autoplay');
    }

    public function test_student_cannot_view_inactive_video(): void
    {
        $video = Video::create([
            'category_id' => $this->category->id,
            'title' => 'Video Nonaktif',
            'source_type' => 'youtube',
            'youtube_id' => 'abc12345678',
            'is_active' => false,
        ]);

        $response = $this->actingAs($this->student)->get(route('student.videos.show', $video));

        $response->assertStatus(404);
    }

    public function test_admin_can_upload_valid_mp4_video(): void
    {
        Storage::fake('public');

        $mp4Header = "\x00\x00\x00\x20ftypisom\x00\x00\x02\x00isomiso2mp41\x00\x00\x00\x08free";
        $file = UploadedFile::fake()->createWithContent('hewan.mp4', $mp4Header.str_repeat('A', 1024));

        $response = $this->actingAs($this->admin)->post(route('admin.videos.store'), [
            'title' => 'Mengenal Suara Kucing',
            'category_id' => $this->category->id,
            'source_type' => 'upload',
            'video_file' => $file,
            'description' => 'Materi video mengenal kucing.',
            'sort_order' => 1,
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('admin.videos.index'));
        $this->assertDatabaseHas('videos', [
            'title' => 'Mengenal Suara Kucing',
            'source_type' => 'upload',
        ]);

        $video = Video::where('title', 'Mengenal Suara Kucing')->first();
        $this->assertNotNull($video->video_path);
        Storage::disk('public')->assertExists($video->video_path);
    }

    public function test_admin_cannot_upload_non_mp4_file(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($this->admin)->post(route('admin.videos.store'), [
            'title' => 'Video Gagal',
            'category_id' => $this->category->id,
            'source_type' => 'upload',
            'video_file' => $file,
        ]);

        $response->assertSessionHasErrors('video_file');
        $this->assertDatabaseMissing('videos', [
            'title' => 'Video Gagal',
        ]);
    }

    public function test_admin_can_create_youtube_video_with_valid_urls(): void
    {
        $urls = [
            'https://www.youtube.com/watch?v=dQw4w9WgXcQ' => 'dQw4w9WgXcQ',
            'https://youtu.be/dQw4w9WgXcQ' => 'dQw4w9WgXcQ',
            'https://www.youtube.com/embed/dQw4w9WgXcQ' => 'dQw4w9WgXcQ',
            'dQw4w9WgXcQ' => 'dQw4w9WgXcQ',
        ];

        foreach ($urls as $url => $expectedId) {
            $response = $this->actingAs($this->admin)->post(route('admin.videos.store'), [
                'title' => 'Lagu '.$expectedId.uniqid(),
                'category_id' => $this->category->id,
                'source_type' => 'youtube',
                'youtube_url' => $url,
            ]);

            $response->assertRedirect(route('admin.videos.index'));
            $this->assertDatabaseHas('videos', [
                'youtube_id' => $expectedId,
            ]);
        }
    }

    public function test_admin_cannot_submit_invalid_youtube_url(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.videos.store'), [
            'title' => 'Invalid URL',
            'category_id' => $this->category->id,
            'source_type' => 'youtube',
            'youtube_url' => 'https://vimeo.com/12345678',
        ]);

        $response->assertSessionHasErrors('youtube_url');
        $this->assertDatabaseMissing('videos', [
            'title' => 'Invalid URL',
        ]);
    }

    public function test_admin_can_toggle_and_delete_video(): void
    {
        Storage::fake('public');
        $fakePath = 'videos/todelete.mp4';
        Storage::disk('public')->put($fakePath, 'dummy content');

        $video = Video::create([
            'title' => 'Video Hapus',
            'source_type' => 'upload',
            'video_path' => $fakePath,
            'is_active' => true,
        ]);

        // Toggle
        $response = $this->actingAs($this->admin)->post(route('admin.videos.toggle', $video));
        $response->assertRedirect();
        $this->assertFalse($video->fresh()->is_active);

        // Delete
        $deleteResponse = $this->actingAs($this->admin)->delete(route('admin.videos.destroy', $video));
        $deleteResponse->assertRedirect(route('admin.videos.index'));
        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
        Storage::disk('public')->assertMissing($fakePath);
    }
}
