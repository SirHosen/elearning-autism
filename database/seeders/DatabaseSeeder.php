<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Material;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Validasi Keamanan Environment Produksi
        if (app()->environment('production')) {
            if (empty(env('ADMIN_EMAIL')) || empty(env('ADMIN_PASSWORD'))) {
                throw new \RuntimeException(
                    'Seeder produksi gagal: Variabel ADMIN_EMAIL dan ADMIN_PASSWORD wajib diisi pada .env di lingkungan production!'
                );
            }
        }

        // 2. Buat Akun Admin Guru
        $adminEmail = env('ADMIN_EMAIL', 'guru@slb.test');
        $adminUser = User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => env('ADMIN_NAME', 'Guru SLB BC'),
                'username' => 'guru',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password123')),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        UserSetting::updateOrCreate(
            ['user_id' => $adminUser->id],
            [
                'volume' => 70,
                'animation_level' => 'minimal',
                'font_scale' => 'normal',
                'theme' => 'calm',
                'sfx_enabled' => false,
            ]
        );

        // 3. Siapkan Direktori Storage Aset
        $storageDir = storage_path('app/public/materials');
        if (! File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        // 4. Seed Kategori (Aktif & Nonaktif)
        $categoriesData = [
            [
                'name' => 'Hewan',
                'slug' => 'hewan',
                'description' => 'Mengenal berbagai hewan lucu dan suaranya di sekitar kita.',
                'color_key' => 'blue',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Warna',
                'slug' => 'warna',
                'description' => 'Mengenal warna-warni ceria yang indah.',
                'color_key' => 'green',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Benda',
                'slug' => 'benda',
                'description' => 'Mengenal benda-benda yang ada di sekolah dan di rumah.',
                'color_key' => 'yellow',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Huruf',
                'slug' => 'huruf',
                'description' => 'Belajar mengenal alfabet A sampai Z.',
                'color_key' => 'lavender',
                'sort_order' => 4,
                'is_active' => false,
            ],
            [
                'name' => 'Angka',
                'slug' => 'angka',
                'description' => 'Belajar berhitung dan mengenal angka 1 sampai 10.',
                'color_key' => 'peach',
                'sort_order' => 5,
                'is_active' => false,
            ],
            [
                'name' => 'Kegiatan Sehari-hari',
                'slug' => 'kegiatan',
                'description' => 'Mengenal rutinitas mandi, makan, tidur, dan belajar.',
                'color_key' => 'blue',
                'sort_order' => 6,
                'is_active' => false,
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = Category::updateOrCreate(
                ['slug' => $catData['slug']],
                $catData
            );
        }

        // 5. Seed 30 Materi Awal (10 Hewan, 10 Warna, 10 Benda)
        $materialsData = [
            'hewan' => [
                ['label' => 'Kucing', 'sentence' => 'Ini kucing berbulu lembut.', 'color' => '#E08E45'],
                ['label' => 'Anjing', 'sentence' => 'Ini anjing setia dan ramah.', 'color' => '#A06B43'],
                ['label' => 'Ayam', 'sentence' => 'Ini ayam berkokok di pagi hari.', 'color' => '#D84315'],
                ['label' => 'Bebek', 'sentence' => 'Ini bebek berenang di kolam.', 'color' => '#FDD835'],
                ['label' => 'Sapi', 'sentence' => 'Ini sapi penghasil susu bergizi.', 'color' => '#424242'],
                ['label' => 'Kambing', 'sentence' => 'Ini kambing suka makan rumput.', 'color' => '#8D6E63'],
                ['label' => 'Ikan', 'sentence' => 'Ini ikan berenang di dalam air.', 'color' => '#0288D1'],
                ['label' => 'Kuda', 'sentence' => 'Ini kuda berlari sangat cepat.', 'color' => '#6D4C41'],
                ['label' => 'Gajah', 'sentence' => 'Ini gajah bertubuh besar dan kuat.', 'color' => '#78909C'],
                ['label' => 'Singa', 'sentence' => 'Ini singa si raja rimba.', 'color' => '#FFA000'],
            ],
            'warna' => [
                ['label' => 'Merah', 'sentence' => 'Warna merah seperti buah apel.', 'color' => '#E53935'],
                ['label' => 'Biru', 'sentence' => 'Warna biru seperti langit yang cerah.', 'color' => '#1E88E5'],
                ['label' => 'Kuning', 'sentence' => 'Warna kuning seperti matahari pagi.', 'color' => '#FDD835'],
                ['label' => 'Hijau', 'sentence' => 'Warna hijau seperti daun segar.', 'color' => '#43A047'],
                ['label' => 'Putih', 'sentence' => 'Warna putih seperti kapas bersih.', 'color' => '#FAFAFA'],
                ['label' => 'Hitam', 'sentence' => 'Warna hitam seperti malam gelap.', 'color' => '#212121'],
                ['label' => 'Oranye', 'sentence' => 'Warna oranye seperti buah jeruk manis.', 'color' => '#FB8C00'],
                ['label' => 'Ungu', 'sentence' => 'Warna ungu seperti buah anggur lezat.', 'color' => '#8E24AA'],
                ['label' => 'Merah Muda', 'sentence' => 'Warna merah muda seperti bunga mawar.', 'color' => '#F06292'],
                ['label' => 'Cokelat', 'sentence' => 'Warna cokelat seperti kayu pohon.', 'color' => '#6D4C41'],
            ],
            'benda' => [
                ['label' => 'Bola', 'sentence' => 'Bola bulat untuk bermain bersama.', 'color' => '#D32F2F'],
                ['label' => 'Buku', 'sentence' => 'Buku jendela ilmu pengetahuan kita.', 'color' => '#1976D2'],
                ['label' => 'Kursi', 'sentence' => 'Kursi nyaman tempat kita duduk belajar.', 'color' => '#8D6E63'],
                ['label' => 'Meja', 'sentence' => 'Meja tempat menulis dan meletakkan buku.', 'color' => '#5D4037'],
                ['label' => 'Gelas', 'sentence' => 'Gelas bersih untuk minum air sehat.', 'color' => '#26C6DA'],
                ['label' => 'Piring', 'sentence' => 'Piring tempat meletakkan makanan lezat.', 'color' => '#78909C'],
                ['label' => 'Sendok', 'sentence' => 'Sendok untuk makan dengan rapi.', 'color' => '#9E9E9E'],
                ['label' => 'Tas', 'sentence' => 'Tas sekolah untuk membawa perlengkapan.', 'color' => '#7B1FA2'],
                ['label' => 'Sepatu', 'sentence' => 'Sepatu bersih melindungi kaki kita.', 'color' => '#37474F'],
                ['label' => 'Topi', 'sentence' => 'Topi melindungi kepala dari terik matahari.', 'color' => '#F57C00'],
            ],
        ];

        foreach ($materialsData as $catSlug => $items) {
            $cat = $categories[$catSlug];
            $order = 1;
            foreach ($items as $m) {
                $filename = strtolower($catSlug.'-'.$order).'.svg';
                $svgContent = $this->generateSvgIllustration($m['label'], $m['color'], $catSlug === 'warna');
                File::put($storageDir.'/'.$filename, $svgContent);

                Material::updateOrCreate(
                    [
                        'category_id' => $cat->id,
                        'label' => $m['label'],
                    ],
                    [
                        'image_path' => 'materials/'.$filename,
                        'audio_path' => null, // Suara otomatis TTS
                        'short_sentence' => $m['sentence'],
                        'sort_order' => $order++,
                        'is_active' => true,
                    ]
                );
            }
        }

        // Panggil DemoSeeder jika di lingkungan local atau testing
        if (app()->environment('local', 'testing')) {
            $this->call(DemoSeeder::class);
        }
    }

    private function generateSvgIllustration(string $label, string $color, bool $isColor): string
    {
        if ($isColor) {
            return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="512" height="512">
  <rect width="512" height="512" fill="#F8FAFC" rx="40"/>
  <circle cx="256" cy="256" r="180" fill="{$color}" stroke="#CBD5E1" stroke-width="8"/>
</svg>
SVG;
        }

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="512" height="512">
  <rect width="512" height="512" fill="#F8FAFC" rx="40"/>
  <circle cx="256" cy="256" r="160" fill="{$color}" opacity="0.15"/>
  <rect x="136" y="136" width="240" height="240" rx="36" fill="{$color}"/>
  <circle cx="216" cy="216" r="16" fill="#FFFFFF"/>
  <circle cx="296" cy="216" r="16" fill="#FFFFFF"/>
  <path d="M 216 280 Q 256 316 296 280" stroke="#FFFFFF" stroke-width="12" fill="none" stroke-linecap="round"/>
</svg>
SVG;
    }
}
