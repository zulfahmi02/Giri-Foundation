<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentCategory extends Model
{
    /** @use HasFactory<\Database\Factories\ContentCategoryFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
    ];

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
