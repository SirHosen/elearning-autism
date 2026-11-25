<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $date = $request->input('date', now()->toDateString());

        $studentsQuery = User::where('role', 'siswa')->orderBy('name');
        if ($request->filled('class_name')) {
            $studentsQuery->where('class_name', $request->input('class_name'));
        }

        $students = $studentsQuery->get();
        $classes = User::where('role', 'siswa')
            ->whereNotNull('class_name')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        $attendances = Attendance::whereDate('attendance_date', $date)
            ->get()
            ->keyBy('user_id');

        $statusSummary = [
            'total' => $students->count(),
            'hadir' => 0,
            'izin' => 0,
            'sakit' => 0,
            'alpa' => 0,
            'belum' => 0,
        ];

        foreach ($students as $student) {
            $st = $attendances->get($student->id)?->status;
            if ($st && isset($statusSummary[$st])) {
                $statusSummary[$st]++;
            } else {
                $statusSummary['belum']++;
            }
        }

        return view('admin.attendances.index', compact('students', 'attendances', 'date', 'classes', 'statusSummary'));
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'status' => 'required|in:hadir,izin,sakit,alpa',
            'note' => 'nullable|string|max:120',
        ]);

        $attendance = Attendance::where('user_id', $student->id)
            ->whereDate('attendance_date', $validated['date'])
            ->first();

        if ($attendance) {
            $attendance->update([
                'status' => $validated['status'],
                'checked_in_at' => $validated['status'] === 'hadir' ? now() : null,
                'source' => 'admin',
                'recorded_by' => auth()->id(),
                'note' => $validated['note'] ?? null,
            ]);
        } else {
            Attendance::create([
                'user_id' => $student->id,
                'attendance_date' => $validated['date'],
                'status' => $validated['status'],
                'checked_in_at' => $validated['status'] === 'hadir' ? now() : null,
                'source' => 'admin',
                'recorded_by' => auth()->id(),
                'note' => $validated['note'] ?? null,
            ]);
        }

        return back()->with('success', "Status absensi untuk '{$student->name}' berhasil diperbarui.");
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $date = $request->input('date', now()->toDateString());

        $students = User::where('role', 'siswa')->orderBy('name')->get();
        $attendances = Attendance::whereDate('attendance_date', $date)->get()->keyBy('user_id');

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"rekap-absensi-{$date}.csv\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($students, $attendances, $date) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel compatibility
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, ['No', 'Nama Siswa', 'Username', 'Tanggal', 'Status', 'Waktu Masuk', 'Pencatat', 'Catatan']);

            foreach ($students as $index => $student) {
                $att = $attendances->get($student->id);
                $status = $att ? ucfirst($att->status) : 'Belum Absen';
                $time = $att?->checked_in_at ? $att->checked_in_at->format('H:i:s') : '-';
                $source = $att ? ($att->source === 'siswa' ? 'Mandiri' : 'Guru') : '-';
                $note = $att?->note ?? '-';

                fputcsv($handle, [
                    $index + 1,
                    $student->name,
                    $student->username,
                    $date,
                    $status,
                    $time,
                    $source,
                    $note,
                ]);
            }

            fclose($handle);
        }, 200, $headers);
    }
}
