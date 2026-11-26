<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessibilityAndErrorPagesTest extends TestCase
{
    use RefreshDatabase;

    private User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->student = User::factory()->create([
            'role' => 'siswa',
            'username' => 'siswa_akses',
        ]);
    }

    public function test_404_page_displays_friendly_calm_message(): void
    {
        $response = $this->actingAs($this->student)->get('/rute-yang-pasti-tidak-ada-12345');

        $response->assertStatus(404);
        $response->assertSee('Halaman Sedang Istirahat');
        $response->assertSee('Ayo kembali ke menu utama');
        $response->assertDontSee('NotFoundHttpException');
    }

    public function test_500_view_renders_friendly_calm_message(): void
    {
        $view = view('errors.500')->render();

        $this->assertStringContainsString('Sedang Ada Perbaikan Kecil', $view);
        $this->assertStringContainsString('Kembali ke Menu Utama', $view);
    }

    public function test_403_page_displays_friendly_calm_message(): void
    {
        $response = $this->actingAs($this->student)->get('/admin');

        $response->assertStatus(403);
        $response->assertSee('Halaman Terkunci');
        $response->assertSee('Kembali ke Beranda');
    }

    public function test_lexend_font_files_are_accessible_locally(): void
    {
        $weights = [400, 500, 600, 700];

        foreach ($weights as $weight) {
            $path = public_path("fonts/lexend/lexend-{$weight}.woff2");
            $this->assertFileExists($path, "Font Lexend weight {$weight} harus tersedia di lokal.");
            $this->assertGreaterThan(5000, filesize($path));
        }

        foreach ([500, 600, 700] as $weight) {
            $fredokaPath = public_path("fonts/fredoka/fredoka-{$weight}.woff2");
            $this->assertFileExists($fredokaPath, "Font Fredoka weight {$weight} harus tersedia di lokal.");
            $this->assertGreaterThan(5000, filesize($fredokaPath));
        }
    }

    public function test_zero_external_cdn_references_in_views(): void
    {
        $forbiddenCdns = [
            'fonts.googleapis.com',
            'fonts.gstatic.com',
            'cdnjs.cloudflare.com',
            'unpkg.com',
            'cdn.jsdelivr.net',
            'cdn.tailwindcss.com',
        ];

        $viewFiles = glob(resource_path('views/**/*.blade.php'));
        $viewFiles = array_merge($viewFiles, glob(resource_path('views/**/**/*.blade.php')));
        $viewFiles = array_merge($viewFiles, glob(resource_path('views/*.blade.php')));
        $viewFiles = array_unique($viewFiles);

        $this->assertNotEmpty($viewFiles);

        foreach ($viewFiles as $file) {
            $content = file_get_contents($file);
            foreach ($forbiddenCdns as $cdn) {
                $this->assertStringNotContainsString(
                    $cdn,
                    $content,
                    "Berkas view [{$file}] terdeteksi memuat CDN eksternal: {$cdn}"
                );
            }
        }
    }

    public function test_error_pages_use_valid_tokens_and_role_aware_return_links(): void
    {
        $admin = User::factory()->admin()->create();

        // Admin hitting missing route gets link back to Panel Guru
        $admin404 = $this->actingAs($admin)->get('/admin/rute-tidak-ditemukan-999');
        $admin404->assertStatus(404);
        $admin404->assertSee('Kembali ke Panel Guru');
        $admin404->assertSee('href="/admin"', false);
        $admin404->assertDontSee('--radius-xl');
        $admin404->assertDontSee('--radius-full');

        // Student hitting missing route gets link back to Beranda
        $student404 = $this->actingAs($this->student)->get('/rute-siswa-tidak-ada');
        $student404->assertStatus(404);
        $student404->assertSee('href="/beranda"', false);
        $student404->assertSee('Kembali ke Menu Utama');

        // Guest hitting missing route gets link to login page (/masuk)
        auth()->logout();
        $guest404 = $this->get('/halaman-publik-tidak-ada');
        $guest404->assertStatus(404);
        $guest404->assertSee('href="/masuk"', false);

        // 500 error view uses valid component classes and defined tokens
        $view500 = view('errors.500')->render();
        $this->assertStringContainsString('error-card', $view500);
        $this->assertStringNotContainsString('--radius-xl', $view500);
        $this->assertStringNotContainsString('--radius-full', $view500);
    }
}
