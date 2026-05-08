<?php

namespace App\Support;

use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class FilamentSlugGenerator
{
    public static function source(
        TextInput $field,
        string $slugField = 'slug',
        ?Closure $shouldGenerate = null,
    ): TextInput {
        return $field
            ->live(onBlur: true)
            ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) use ($slugField, $shouldGenerate): void {
                if (($shouldGenerate !== null) && ! $shouldGenerate($get)) {
                    return;
                }

                $currentSlug = (string) ($get($slugField) ?? '');
                $expectedSlugFromOldState = Str::slug((string) ($old ?? ''));

                if (filled($currentSlug) && ($currentSlug !== $expectedSlugFromOldState)) {
                    return;
                }

                $set($slugField, Str::slug((string) ($state ?? '')));
            });
    }

    public static function field(string $sourceField = 'title'): TextInput
    {
        return TextInput::make('slug')
            ->label('Alamat URL')
            ->required()
            ->readOnly()
            ->helperText('Otomatis dibuat dari judul atau nama. Tidak perlu diisi manual.')
            ->dehydrateStateUsing(function (Get $get, ?string $state) use ($sourceField): string {
                $slugSource = filled($state) ? $state : (string) ($get($sourceField) ?? '');

                return Str::slug($slugSource);
            });
    }
}
