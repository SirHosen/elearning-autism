<?php

namespace App\Providers;

use App\Models\Attendance;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            try {
                $today = now()->toDateString();
                $activeStudentsCount = User::where('role', 'siswa')->where('is_active', true)->count();
                $attendedTodayIds = Attendance::whereDate('attendance_date', $today)
                    ->pluck('user_id');

                $unattendedTodayCount = User::where('role', 'siswa')
                    ->where('is_active', true)
                    ->whereNotIn('id', $attendedTodayIds)
                    ->count();

                $view->with('adminSidebarCounts', [
                    'students' => $activeStudentsCount,
                    'unattendedToday' => $unattendedTodayCount,
                    'materials' => Material::where('is_active', true)->count(),
                    'quizzes' => Quiz::where('is_active', true)->count(),
                ]);
            } catch (\Throwable $e) {
                $view->with('adminSidebarCounts', [
                    'students' => 0,
                    'unattendedToday' => 0,
                    'materials' => 0,
                    'quizzes' => 0,
                ]);
            }
        });
    }
}
