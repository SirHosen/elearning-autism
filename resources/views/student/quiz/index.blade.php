@extends('layouts.student')

@section('title', 'Kuis Bergambar')

@section('content')
<div class="container" style="padding-top: var(--space-6); padding-bottom: var(--space-10);">
    {{-- Header Siswa --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-6); flex-wrap: wrap; gap: var(--space-4);">
        <a href="{{ route('student.home') }}" class="btn btn-secondary" style="font-size: 1.125rem; font-weight: 600; padding: var(--space-3) var(--space-6); border-radius: var(--radius-full); display: inline-flex; align-items: center; gap: var(--space-2);">
            <span>&larr;</span> Kembali
        </a>
        <h1 style="margin: 0; font-size: 1.75rem; color: var(--text);">Kuis Bergambar</h1>
    </div>

    @if($quizzes->isEmpty())
        <div class="card" style="text-align: center; padding: var(--space-10); max-width: 500px; margin: 0 auto;">
            <div style="font-size: 3rem; margin-bottom: var(--space-2);">⭐</div>
            <h2 style="font-size: 1.25rem; margin-bottom: var(--space-2);">Belum Ada Kuis</h2>
            <p style="color: var(--text-muted); margin-bottom: var(--space-6);">Bapak/Ibu guru akan menambahkan kuis bergambar di sini segera.</p>
            <a href="{{ route('student.home') }}" class="btn btn-primary" style="border-radius: var(--radius-full);">Kembali ke Menu Utama</a>
        </div>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: var(--space-6);">
            @foreach($quizzes as $quiz)
                <a href="{{ route('student.quizzes.play', $quiz) }}" class="card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column; padding: var(--space-6); transition: transform var(--transition-base), box-shadow var(--transition-base); border-radius: var(--radius-lg); border-top: 6px solid #F59E0B;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: var(--space-4);">
                        <span class="badge" style="background: var(--surface-alt); font-size: 0.8125rem;">
                            {{ $quiz->category?->name ?? 'Umum' }}
                        </span>
                        <span style="font-size: 1.75rem;">⭐</span>
                    </div>
                    <h3 style="margin: 0 0 var(--space-4) 0; font-size: 1.25rem; font-weight: 700; color: var(--text); flex-grow: 1;">
                        {{ $quiz->title }}
                    </h3>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: var(--space-3); margin-top: auto;">
                        <span style="font-size: 0.875rem; color: var(--text-muted); font-weight: 500;">
                            {{ $quiz->questions_count }} Soal Bergambar
                        </span>
                        <span style="color: var(--primary); font-weight: 700; font-size: 1rem; display: flex; align-items: center; gap: var(--space-1);">
                            Mulai Kuis &rarr;
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
