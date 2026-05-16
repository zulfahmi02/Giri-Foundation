<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use App\Support\TeamMemberStructureSlots;
use Database\Factories\TeamMemberFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeamMember extends Model
{
    /** @use HasFactory<TeamMemberFactory> */
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
            get: fn (): ?string => PublicStorageUrl::resolve($this->getRawOriginal('photo_url'), verifyPublicDisk: true),
        );
    }

    /**
     * @param  Collection<int, self>  $teamMembers
     * @return Collection<int, self>
     */
    public static function buildHierarchy(Collection $teamMembers): Collection
    {
        $teamMembersById = $teamMembers->keyBy('id');
        $teamMembersBySlot = $teamMembers
            ->filter(fn (self $teamMember): bool => filled($teamMember->structure_slot))
            ->keyBy('structure_slot');
        $structureDefinitions = TeamMemberStructureSlots::definitions();

        $childrenByParent = $teamMembers->groupBy(
            function (self $teamMember) use ($teamMembersById, $teamMembersBySlot, $structureDefinitions): string|int {
                $parentSlot = filled($teamMember->structure_slot)
                    ? ($structureDefinitions[$teamMember->structure_slot]['parent_slot'] ?? null)
                    : null;

                $slotParent = filled($parentSlot)
                    ? $teamMembersBySlot->get($parentSlot)
                    : null;

                if ($slotParent instanceof self && $slotParent->getKey() !== $teamMember->getKey()) {
                    return $slotParent->getKey();
                }

                if ($teamMember->parent_id !== null && $teamMembersById->has($teamMember->parent_id)) {
                    return $teamMember->parent_id;
                }

                return 'root';
            },
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
