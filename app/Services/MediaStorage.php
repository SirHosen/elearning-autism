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

    public function deleteIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
