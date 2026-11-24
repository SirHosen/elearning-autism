<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $today = now()->format('Y-m-d');

        $attendanceToday = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        return view('student.home', [
            'user' => $user,
            'attendanceToday' => $attendanceToday,
            'hasAttendedToday' => $attendanceToday?->status === 'hadir',
        ]);
    }
}
