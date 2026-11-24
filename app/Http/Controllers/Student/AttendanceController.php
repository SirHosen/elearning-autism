<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        $hasAttendedToday = $attendance && $attendance->status === 'hadir';

        return view('student.attendance.index', [
            'user' => $user,
            'attendance' => $attendance,
            'hasAttendedToday' => $hasAttendedToday,
        ]);
    }

    public function submit(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $today = now()->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('attendance_date', $today)
            ->first();

        if (! $attendance) {
            Attendance::create([
                'user_id' => $user->id,
                'attendance_date' => $today,
                'status' => 'hadir',
                'checked_in_at' => now(),
                'source' => 'siswa',
            ]);
        }

        return redirect()->route('student.attendance.index')->with('success_attended', true);
    }
}
