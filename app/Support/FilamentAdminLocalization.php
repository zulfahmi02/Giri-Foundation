<?php

namespace App\Support;

use Filament\Forms\Components\Field;
use Filament\Infolists\Components\Entry;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;

class FilamentAdminLocalization
{
    public static function register(): void
    {
        Field::configureUsing(fn (Field $field): Field => $field->translateLabel());
        Entry::configureUsing(fn (Entry $entry): Entry => $entry->translateLabel());
        Column::configureUsing(fn (Column $column): Column => $column->translateLabel());
        BaseFilter::configureUsing(fn (BaseFilter $filter): BaseFilter => $filter->translateLabel());
    }
}
