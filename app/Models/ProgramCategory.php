<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ProgramCategoryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
