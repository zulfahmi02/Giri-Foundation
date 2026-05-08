<?php

namespace App\Support;

use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\FileUpload;

class FilamentImageUpload
{
    /**
     * @return array{name: string, size: int, type: string|null, url: string}|null
     */
    private static function uploadedFilePreview(BaseFileUpload $component, string $file, string|array|null $storedFileNames): ?array
    {
        $url = PublicStorageUrl::resolve($file);

        if ($url === null) {
            return null;
        }

        $path = parse_url($file, PHP_URL_PATH) ?: $file;
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $storedName = $component->isMultiple()
            ? (is_array($storedFileNames) ? ($storedFileNames[$file] ?? null) : null)
            : (is_string($storedFileNames) ? $storedFileNames : null);

        return [
            'name' => $storedName ?? basename($path),
            'size' => 0,
            'type' => match ($extension) {
                'jpg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
                default => null,
            },
            'url' => $url,
        ];
    }

    public static function make(string $name, string $directory, ?string $label = null, int $maxSize = 4096): FileUpload
    {
        $component = FileUpload::make($name)
            ->image()
            ->imageEditor()
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->fetchFileInformation(false)
            ->getUploadedFileUsing(self::uploadedFilePreview(...))
            ->maxSize($maxSize)
            ->helperText('Unggah gambar JPG, PNG, atau WebP dari perangkat. Maksimal '.(int) ($maxSize / 1024).' MB.')
            ->openable()
            ->downloadable()
            ->columnSpanFull();

        if ($label !== null) {
            $component->label($label);
        }

        return $component;
    }
}
