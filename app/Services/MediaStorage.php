<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MediaStorage
{
    private const ALLOWED_AUDIO_MIMES = [
        'audio/mpeg',
        'audio/mp3',
        'audio/ogg',
        'audio/wav',
        'audio/x-wav',
    ];

    private const ALLOWED_VIDEO_MIMES = [
        'video/mp4',
    ];

    public function storeAudio(UploadedFile $file, string $directory = 'audio'): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        if (! in_array($mime, self::ALLOWED_AUDIO_MIMES, true)) {
            throw ValidationException::withMessages([
                'audio' => 'Format audio harus berupa MP3, OGG, atau WAV.',
            ]);
        }

        $extension = $file->getClientOriginalExtension() ?: 'mp3';
        $filename = Str::random(40).'.'.$extension;

        return $file->storeAs($directory, $filename, 'public');
    }

    public function storeVideo(UploadedFile $file, string $directory = 'videos'): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        $isMp4 = in_array($mime, self::ALLOWED_VIDEO_MIMES, true)
            || in_array($file->getMimeType(), self::ALLOWED_VIDEO_MIMES, true)
            || strtolower($file->getClientOriginalExtension()) === 'mp4';

        if (! $isMp4) {
            throw ValidationException::withMessages([
                'video_file' => 'Format video harus berupa MP4.',
            ]);
        }

        $filename = Str::random(40).'.mp4';

        return $file->storeAs($directory, $filename, 'public');
    }

    public static function extractYouTubeId(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $trimmed = trim($url);
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $trimmed)) {
            return $trimmed;
        }

        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=|shorts\/))([a-zA-Z0-9_-]{11})/', $trimmed, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function deleteIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
