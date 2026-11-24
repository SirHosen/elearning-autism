<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Category;
use App\Models\Game;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat 3 Siswa Demo
        $students = [
            ['name' => 'Siswa Demo A', 'username' => 'siswa_a', 'class' => 'Kelas 1'],
            ['name' => 'Siswa Demo B', 'username' => 'siswa_b', 'class' => 'Kelas 1'],
            ['name' => 'Siswa Demo C', 'username' => 'siswa_c', 'class' => 'Kelas 2'],
        ];

        $studentModels = [];
        foreach ($students as $s) {
            $user = User::updateOrCreate(
                ['username' => $s['username']],
                [
                    'name' => $s['name'],
                    'password' => Hash::make('1234'), // PIN 4 angka
                    'role' => 'siswa',
                    'class_name' => $s['class'],
                    'is_active' => true,
                ]
            );

            UserSetting::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'volume' => 70,
                    'animation_level' => 'minimal',
                    'font_scale' => 'normal',
                    'theme' => 'calm',
                    'sfx_enabled' => false,
                ]
            );

            $studentModels[] = $user;
        }

        // 2. Buat Contoh Permainan Edukasi
        $hewanCat = Category::where('slug', 'hewan')->first();
        $warnaCat = Category::where('slug', 'warna')->first();
        $bendaCat = Category::where('slug', 'benda')->first();

        if ($hewanCat) {
            Game::updateOrCreate(
                ['title' => 'Tebak Nama Hewan'],
                [
                    'category_id' => $hewanCat->id,
                    'game_type' => 'pilih_benar',
                    'option_count' => 3,
                    'round_count' => 5,
                    'instruction_text' => 'Pilih hewan yang benar ya.',
                    'sort_order' => 1,
                    'is_active' => true,
                ]
            );
        }

        if ($warnaCat) {
            Game::updateOrCreate(
                ['title' => 'Cocokkan Warna Ceria'],
                [
                    'category_id' => $warnaCat->id,
                    'game_type' => 'cocokkan',
                    'option_count' => 3,
                    'round_count' => 4,
                    'instruction_text' => 'Cocokkan warna dengan namanya.',
                    'sort_order' => 2,
                    'is_active' => true,
                ]
            );
        }

        if ($bendaCat) {
            Game::updateOrCreate(
                ['title' => 'Kenali Benda di Sekitar'],
                [
                    'category_id' => $bendaCat->id,
                    'game_type' => 'seret_tempatkan',
                    'option_count' => 3,
                    'round_count' => 5,
                    'instruction_text' => 'Pilih benda ke tempatnya.',
                    'sort_order' => 3,
                    'is_active' => true,
                ]
            );
        }

        // 3. Buat Contoh Kuis Bergambar
        if ($hewanCat && $hewanCat->materials()->count() >= 3) {
            $quiz = Quiz::updateOrCreate(
                ['title' => 'Kuis Pengenalan Hewan'],
                [
                    'category_id' => $hewanCat->id,
                    'sort_order' => 1,
                    'is_active' => true,
                ]
            );

            $materials = $hewanCat->materials()->take(5)->get();
            $order = 1;
            foreach ($materials as $target) {
                $q = QuizQuestion::updateOrCreate(
                    [
                        'quiz_id' => $quiz->id,
                        'correct_material_id' => $target->id,
                    ],
                    [
                        'prompt_text' => 'Mana yang bernama "'.$target->label.'"?',
                        'sort_order' => $order++,
                    ]
                );

                // Tambahkan opsi benar
                QuizQuestionOption::firstOrCreate([
                    'question_id' => $q->id,
                    'material_id' => $target->id,
                ]);

                // Tambahkan 2 pengecoh
                $distractors = $hewanCat->materials()
                    ->where('id', '!=', $target->id)
                    ->inRandomOrder()
                    ->take(2)
                    ->get();

                foreach ($distractors as $d) {
                    QuizQuestionOption::firstOrCreate([
                        'question_id' => $q->id,
                        'material_id' => $d->id,
                    ]);
                }
            }
        }

        // 4. Buat Contoh Riwayat Absensi
        if (! empty($studentModels)) {
            $today = now()->format('Y-m-d');
            $yesterday = now()->subDay()->format('Y-m-d');

            // Siswa A hadir hari ini
            Attendance::updateOrCreate(
                [
                    'user_id' => $studentModels[0]->id,
                    'attendance_date' => $today,
                ],
                [
                    'status' => 'hadir',
                    'checked_in_at' => now(),
                    'source' => 'siswa',
                ]
            );

            // Kemarin
            Attendance::updateOrCreate(
                [
                    'user_id' => $studentModels[0]->id,
                    'attendance_date' => $yesterday,
                ],
                [
                    'status' => 'hadir',
                    'checked_in_at' => now()->subDay()->setHour(7)->setMinute(45),
                    'source' => 'siswa',
                ]
            );

            // Siswa B izin kemarin
            Attendance::updateOrCreate(
                [
                    'user_id' => $studentModels[1]->id,
                    'attendance_date' => $yesterday,
                ],
                [
                    'status' => 'izin',
                    'source' => 'admin',
                    'note' => 'Izin keperluan keluarga',
                ]
            );
        }
    }
}
