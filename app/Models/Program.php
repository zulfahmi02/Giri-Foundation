<?php

namespace App\Models;

use Database\Factories\ProgramFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    /** @use HasFactory<ProgramFactory> */
    use HasFactory;

    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'description',
        'category_id',
        'status',
        'phase',
        'start_date',
        'end_date',
        'location_name',
        'province',
        'city',
        'target_beneficiaries',
        'beneficiaries_count',
        'budget_amount',
        'featured_image_url',
        'is_featured',
        'published_at',
        'created_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'budget_amount' => 'decimal:2',
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Program $program): void {
            if ($program->status === 'published' && blank($program->published_at)) {
                $program->published_at = now();
            }
        });
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function scopeInPhase(Builder $query, string $phase): void
    {
        $query->where('phase', $phase);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProgramCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function galleries(): HasMany
    {
        return $this->hasMany(ProgramGallery::class)->orderBy('sort_order');
    }

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'program_partners')->withPivot('role');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class)->orderByDesc('activity_date');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
