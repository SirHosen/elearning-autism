<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $rawSlug = $this->input('slug');
        $rawName = (string) $this->input('name', '');
        $normalized = Str::slug($rawSlug ?: $rawName);

        if ($normalized !== '') {
            $this->merge(['slug' => $normalized]);
        }
    }

    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;

        return [
            'name' => ['required', 'string', 'max:50'],
            'slug' => ['nullable', 'string', 'max:50', Rule::unique('categories', 'slug')->ignore($categoryId)],
            'description' => ['nullable', 'string', 'max:500'],
            'color_key' => ['required', 'in:blue,green,yellow,lavender,peach'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.max' => 'Nama kategori maksimal 50 karakter.',
            'slug.unique' => 'Slug URL kategori sudah digunakan.',
            'color_key.required' => 'Warna kartu kategori wajib dipilih.',
            'color_key.in' => 'Pilihan warna tidak valid.',
        ];
    }
}
