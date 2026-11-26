<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRequest;
use App\Models\User;
use App\Models\UserSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'siswa')
            ->withCount([
                'attendances as attended_days_count' => fn ($q) => $q->where('status', 'hadir'),
                'quizAttempts as completed_quizzes_count' => fn ($q) => $q->whereNotNull('finished_at'),
                'gameSessions',
            ])
            ->orderBy('name');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sq) use ($q) {
                $sq->where('name', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%")
                    ->orWhere('class_name', 'like', "%{$q}%");
            });
        }

        if ($request->filled('class_name')) {
            $query->where('class_name', $request->input('class_name'));
        }

        if ($request->input('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->input('status') === 'inactive') {
            $query->where('is_active', false);
        }

        $students = $query->paginate(15)->withQueryString();
        $classes = User::where('role', 'siswa')
            ->whereNotNull('class_name')
            ->distinct()
            ->orderBy('class_name')
            ->pluck('class_name');

        $summaryStats = [
            'total' => User::where('role', 'siswa')->count(),
            'active' => User::where('role', 'siswa')->where('is_active', true)->count(),
            'inactive' => User::where('role', 'siswa')->where('is_active', false)->count(),
        ];

        return view('admin.students.index', compact('students', 'classes', 'summaryStats'));
    }

    public function create(): View
    {
        return view('admin.students.create');
    }

    public function store(StudentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $rawPin = $validated['pin'] ?: sprintf('%04d', mt_rand(1000, 9999));

        $student = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => Hash::make($rawPin),
            'role' => 'siswa',
            'class_name' => $validated['class_name'] ?? null,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
        ]);

        UserSetting::create([
            'user_id' => $student->id,
            'volume' => 70,
            'animation_level' => 'minimal',
            'font_scale' => 'normal',
            'theme' => 'calm',
            'sfx_enabled' => false,
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Akun siswa berhasil dibuat.')
            ->with('generated_pin', $rawPin)
            ->with('student_name', $student->name)
            ->with('student_username', $student->username)
            ->with('student_id', $student->id);
    }

    public function edit(User $student): View
    {
        abort_unless($student->isStudent(), 404);

        return view('admin.students.edit', compact('student'));
    }

    public function update(StudentRequest $request, User $student): RedirectResponse
    {
        abort_unless($student->isStudent(), 404);

        $validated = $request->validated();

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'class_name' => $validated['class_name'] ?? null,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : $student->is_active,
        ];

        if (! empty($validated['pin'])) {
            $updateData['password'] = Hash::make($validated['pin']);
        }

        $student->update($updateData);

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function resetPin(User $student): RedirectResponse
    {
        abort_unless($student->isStudent(), 404);

        $newPin = sprintf('%04d', mt_rand(1000, 9999));

        $student->update([
            'password' => Hash::make($newPin),
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'PIN siswa berhasil di-reset.')
            ->with('generated_pin', $newPin)
            ->with('student_name', $student->name)
            ->with('student_username', $student->username)
            ->with('student_id', $student->id);
    }

    public function printCard(User $student): View
    {
        abort_unless($student->isStudent(), 404);

        return view('admin.students.card', compact('student'));
    }

    public function destroy(User $student): RedirectResponse
    {
        abort_unless($student->isStudent(), 404);

        $name = $student->name;
        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', "Akun siswa '{$name}' berhasil diarsipkan.");
    }
}
