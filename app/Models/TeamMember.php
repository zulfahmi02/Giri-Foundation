<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeamMember extends Model
{
    /** @use HasFactory<\Database\Factories\TeamMemberFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'structure_slot',
        'position',
        'division',
        'division_id',
        'parent_id',
        'bio',
        'photo_url',
        'email',
        'linkedin_url',
        'sort_order',
        'is_structural',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'structure_slot' => 'string',
            'parent_id' => 'integer',
            'division_id' => 'integer',
            'sort_order' => 'integer',
            'is_structural' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function divisionRecord(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function scopeStructural(Builder $query): Builder
    {
        return $query->where('is_structural', true);
    }

    protected function publicPhotoUrl(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $photoPath = $this->getRawOriginal('photo_url');

                if (blank($photoPath)) {
                    return null;
                }

                if (Str::startsWith($photoPath, ['http://', 'https://', '//', '/'])) {
                    return $photoPath;
                }

                if (Str::startsWith($photoPath, ['storage/', 'image/'])) {
                    return '/'.ltrim($photoPath, '/');
                }

                return Storage::disk('public')->url($photoPath);
            },
        );
    }

    /**
     * @param  Collection<int, self>  $teamMembers
     * @return Collection<int, self>
     */
    public static function buildHierarchy(Collection $teamMembers): Collection
    {
        $childrenByParent = $teamMembers->groupBy(
            fn (self $teamMember): string|int => $teamMember->parent_id ?? 'root',
        );

        $attachChildren = function (self $teamMember) use (&$attachChildren, $childrenByParent): self {
            $children = new Collection(
                $childrenByParent
                    ->get($teamMember->id, collect())
                    ->map(fn (self $child): self => $attachChildren($child))
                    ->values()
                    ->all(),
            );

            $teamMember->setRelation('children', $children);

            return $teamMember;
        };

        return new Collection(
            $childrenByParent
                ->get('root', collect())
                ->map(fn (self $teamMember): self => $attachChildren($teamMember))
                ->values()
                ->all(),
        );
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
