<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'label' => ['required', 'string', 'max:40'],
            'short_sentence' => ['nullable', 'string', 'max:80'],
            'image' => [$isUpdate ? 'nullable' : 'required', 'file', 'max:4096'],
            'audio' => ['nullable', 'file', 'max:3072'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Kategori materi wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'label.required' => 'Nama materi objek wajib diisi.',
            'label.max' => 'Nama materi objek maksimal 40 karakter.',
            'short_sentence.max' => 'Kalimat pendek maksimal 80 karakter.',
            'image.required' => 'Berkas gambar materi wajib diunggah.',
            'image.max' => 'Ukuran gambar maksimal 4 MB.',
            'audio.max' => 'Ukuran audio maksimal 3 MB.',
        ];
    }
}
