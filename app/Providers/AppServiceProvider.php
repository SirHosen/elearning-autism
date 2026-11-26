<?php

namespace App\Providers;

use App\Models\Attendance;
use App\Models\Material;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Pagination\Paginator;
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
        Paginator::defaultView('vendor.pagination.sahabat');
        Paginator::defaultSimpleView('vendor.pagination.sahabat');

        View::composer('layouts.admin', function ($view) {
            try {
                $today = now()->toDateString();
                $activeStudentIds = User::where('role', 'siswa')
                    ->where('is_active', true)
                    ->pluck('id');

                $activeStudentsCount = $activeStudentIds->count();
                $attendedTodayCount = $activeStudentsCount > 0
                    ? Attendance::whereIn('user_id', $activeStudentIds)
                        ->whereDate('attendance_date', $today)
                        ->distinct('user_id')
                        ->count('user_id')
                    : 0;

                $view->with('adminSidebarCounts', [
                    'students' => $activeStudentsCount,
                    'unattendedToday' => max(0, $activeStudentsCount - $attendedTodayCount),
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
