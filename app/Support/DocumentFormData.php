<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DocumentFormData
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function prepareForFill(array $data): array
    {
        $fileReference = trim((string) ($data['file_url'] ?? ''));

        if (filter_var($fileReference, FILTER_VALIDATE_URL) !== false) {
            $data['external_file_url'] = $fileReference;
            $data['file_url'] = null;
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function prepareForSave(array $data): array
    {
        $externalFileUrl = trim((string) Arr::pull($data, 'external_file_url'));
        $uploadedFilePath = trim((string) ($data['file_url'] ?? ''));

        if ($externalFileUrl === '' && $uploadedFilePath === '') {
            throw ValidationException::withMessages([
                'file_url' => 'Unggah berkas dokumen atau isi URL eksternal dokumen.',
            ]);
        }

        if ($externalFileUrl !== '') {
            $data['file_url'] = $externalFileUrl;
            $data['file_size'] = null;
            $data['file_type'] = static::normalizeFileType(
                currentFileType: $data['file_type'] ?? null,
                fileReference: $externalFileUrl,
            );

            return $data;
        }

        $data['file_type'] = static::normalizeFileType(
            currentFileType: $data['file_type'] ?? null,
            fileReference: $uploadedFilePath,
        );
        $data['file_size'] = static::normalizeFileSize(
            currentFileSize: $data['file_size'] ?? null,
            filePath: $uploadedFilePath,
        );

        return $data;
    }

    private static function normalizeFileType(mixed $currentFileType, string $fileReference): ?string
    {
        if (filled($currentFileType)) {
            return Str::upper((string) $currentFileType);
        }

        $path = parse_url($fileReference, PHP_URL_PATH) ?: $fileReference;
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return $extension === '' ? null : Str::upper($extension);
    }

    private static function normalizeFileSize(mixed $currentFileSize, string $filePath): ?int
    {
        if (filled($currentFileSize)) {
            return (int) $currentFileSize;
        }

        if ($filePath === '' || ! Storage::disk('public')->exists($filePath)) {
            return null;
        }

        return Storage::disk('public')->size($filePath);
    }
}
