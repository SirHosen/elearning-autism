<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $studentId = $this->route('student')?->id ?? $this->route('user')?->id;

        return [
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($studentId)],
            'class_name' => ['nullable', 'string', 'max:50'],
            'pin' => ['nullable', 'digits_between:4,6'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap/panggilan siswa wajib diisi.',
            'username.required' => 'Nama pengguna (username) wajib diisi.',
            'username.unique' => 'Nama pengguna ini sudah digunakan oleh siswa lain.',
            'pin.digits_between' => 'PIN harus terdiri dari 4 sampai 6 angka.',
        ];
    }
}
