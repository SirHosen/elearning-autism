@extends('layouts.admin')

@section('title', 'Kelola Soal: ' . $quiz->title)

@section('content')
<div style="margin-bottom: var(--space-6);">
    <a href="{{ route('admin.quizzes.index') }}" style="color: var(--text-muted); text-decoration: none; font-size: 0.9375rem;">&larr; Kembali ke Daftar Kuis</a>
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: var(--space-4); margin-top: var(--space-2);">
        <div>
            <h1 style="margin: 0; font-size: 1.5rem;">Kelola Soal: {{ $quiz->title }}</h1>
            <p style="margin: var(--space-1) 0 0 0; color: var(--text-muted); font-size: 0.9375rem;">
                Kategori: <strong>{{ $quiz->category?->name ?? 'Umum' }}</strong> &bull; Total Soal: <strong>{{ $quiz->questions->count() }}</strong>
            </p>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 380px; gap: var(--space-8); align-items: start;">
    {{-- Daftar Soal Tersedia --}}
    <div>
        <h2 style="font-size: 1.25rem; margin-bottom: var(--space-4);">Daftar Soal Kuis</h2>

        @forelse($quiz->questions as $index => $question)
            <div class="card" style="margin-bottom: var(--space-4); padding: var(--space-4);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-3);">
                    <div>
                        <span class="badge" style="background: var(--surface-alt); font-weight: 700; margin-right: var(--space-2);">
                            Soal #{{ $index + 1 }}
                        </span>
                        <strong style="font-size: 1.125rem;">{{ $question->prompt_text }}</strong>
                    </div>
                    <form action="{{ route('admin.quizzes.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" style="padding: var(--space-1) var(--space-2); font-size: 0.8125rem;">Hapus</button>
                    </form>
                </div>

                <div style="display: flex; gap: var(--space-4); flex-wrap: wrap; align-items: center; margin-top: var(--space-2);">
                    @foreach($question->options as $option)
                        @php $isCorrect = $option->material_id === $question->correct_material_id; @endphp
                        <div style="display: flex; flex-direction: column; align-items: center; padding: var(--space-2); border: 2px solid {{ $isCorrect ? 'var(--success, #246B4D)' : 'var(--border)' }}; border-radius: var(--radius-md); background: {{ $isCorrect ? 'var(--success-bg, #E3F2EA)' : 'var(--surface-alt)' }};">
                            <img src="{{ asset('storage/' . $option->material->image_path) }}" alt="{{ $option->material->label }}" style="width: 60px; height: 60px; object-fit: contain;">
                            <span style="font-size: 0.8125rem; font-weight: 600; margin-top: var(--space-1); color: var(--text);">
                                {{ $option->material->label }}
                                @if($isCorrect) &check; @endif
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="card" style="text-align: center; padding: var(--space-8); color: var(--text-muted);">
                Belum ada soal pada kuis ini. Tambahkan soal manual melalui formulir di samping.
            </div>
        @endforelse
    </div>

    {{-- Form Tambah Soal Manual --}}
    <div class="card" style="padding: var(--space-5);">
        <h3 style="margin-top: 0; font-size: 1.125rem; margin-bottom: var(--space-4);">+ Tambah Soal Manual</h3>

        <form action="{{ route('admin.quizzes.questions.store', $quiz) }}" method="POST">
            @csrf

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="prompt_text" class="form-label" style="font-weight: 600;">Pertanyaan *</label>
                <input type="text" name="prompt_text" id="prompt_text" class="form-control" value="{{ old('prompt_text', 'Mana yang merupakan ...?') }}" required>
                @error('prompt_text')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: var(--space-4);">
                <label for="correct_material_id" class="form-label" style="font-weight: 600;">Jawaban Benar (Kunci) *</label>
                <select name="correct_material_id" id="correct_material_id" class="form-control" required>
                    <option value="">-- Pilih Materi Kunci --</option>
                    @foreach($quiz->category?->materials ?? [] as $m)
                        <option value="{{ $m->id }}" {{ old('correct_material_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->label }}
                        </option>
                    @endforeach
                </select>
                @error('correct_material_id')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" style="margin-bottom: var(--space-6);">
                <label class="form-label" style="font-weight: 600;">Pilihan Jawaban (Pilih 2-4 materi) *</label>
                <small style="color: var(--text-muted); display: block; margin-bottom: var(--space-2);">Pastikan materi jawaban benar dicentang.</small>
                <div style="display: flex; flex-direction: column; gap: var(--space-2); max-height: 220px; overflow-y: auto; padding: var(--space-2); border: 1px solid var(--border); border-radius: var(--radius-sm);">
                    @foreach($quiz->category?->materials ?? [] as $m)
                        <label style="display: flex; align-items: center; gap: var(--space-2); cursor: pointer; font-size: 0.9375rem;">
                            <input type="checkbox" name="option_ids[]" value="{{ $m->id }}" {{ (is_array(old('option_ids')) && in_array($m->id, old('option_ids'))) ? 'checked' : '' }}>
                            <span>{{ $m->label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('option_ids')
                    <div style="color: var(--danger); font-size: 0.8125rem; margin-top: var(--space-1);">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Simpan Soal</button>
        </form>
    </div>
</div>
@endsection
