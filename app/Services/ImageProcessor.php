<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ImageProcessor
{
    private const ALLOWED_MIMES = [
        'image/jpeg' => 'jpeg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    private const MAX_DIMENSION = 1024;

    private const WEBP_QUALITY = 82;

    /**
     * Memproses gambar: validasi MIME asli, resize maks 1024px, strip metadata, konversi WebP.
     *
     * @throws ValidationException
     */
    public function processAndStore(UploadedFile $file, string $directory = 'materials'): string
    {
        $realMime = $this->detectRealMime($file);

        if (! array_key_exists($realMime, self::ALLOWED_MIMES)) {
            throw ValidationException::withMessages([
                'image' => 'Format gambar harus berupa JPG, PNG, atau WebP yang valid (SVG tidak diizinkan untuk keamanan).',
            ]);
        }

        $sourceImage = match ($realMime) {
            'image/jpeg' => @imagecreatefromjpeg($file->getRealPath()),
            'image/png' => @imagecreatefrompng($file->getRealPath()),
            'image/webp' => @imagecreatefromwebp($file->getRealPath()),
            default => null,
        };

        if (! $sourceImage) {
            throw ValidationException::withMessages([
                'image' => 'Berkas gambar rusak atau tidak dapat diproses oleh sistem.',
            ]);
        }

        $origWidth = imagesx($sourceImage);
        $origHeight = imagesy($sourceImage);

        // Hitung skala proporsional jika lebih dari 1024px
        $targetWidth = $origWidth;
        $targetHeight = $origHeight;

        if ($origWidth > self::MAX_DIMENSION || $origHeight > self::MAX_DIMENSION) {
            $ratio = $origWidth / $origHeight;
            if ($ratio > 1) {
                $targetWidth = self::MAX_DIMENSION;
                $targetHeight = (int) round(self::MAX_DIMENSION / $ratio);
            } else {
                $targetHeight = self::MAX_DIMENSION;
                $targetWidth = (int) round(self::MAX_DIMENSION * $ratio);
            }
        }

        $canvas = imagecreatetruecolor($targetWidth, $targetHeight);

        // Pertahankan transparansi untuk PNG dan WebP
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefilledrectangle($canvas, 0, 0, $targetWidth, $targetHeight, $transparent);
        imagealphablending($canvas, true);

        imagecopyresampled(
            $canvas,
            $sourceImage,
            0, 0, 0, 0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        $filename = Str::random(40).'.webp';
        $relativePath = $directory.'/'.$filename;
        $fullPath = Storage::disk('public')->path($relativePath);

        // Buat direktori jika belum ada
        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        imagewebp($canvas, $fullPath, self::WEBP_QUALITY);

        imagedestroy($sourceImage);
        imagedestroy($canvas);

        return $relativePath;
    }

    private function detectRealMime(UploadedFile $file): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file->getRealPath());
        finfo_close($finfo);

        return $mime ?: $file->getMimeType();
    }
}
