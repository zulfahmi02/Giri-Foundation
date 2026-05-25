<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use Database\Factories\DonationCampaignFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationCampaign extends Model
{
    /** @use HasFactory<DonationCampaignFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'description',
        'target_amount',
        'collected_amount',
        'start_date',
        'end_date',
        'banner_image_url',
        'status',
        'is_featured',
        'published_by',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'target_amount' => 'decimal:2',
            'collected_amount' => 'decimal:2',
            'start_date' => 'date',
            'end_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (DonationCampaign $campaign): void {
            if (! $campaign->is_featured || ! $campaign->isPublishedForFrontend()) {
                return;
            }

            static::query()
                ->whereKeyNot($campaign->id)
                ->where('is_featured', true)
                ->whereIn('status', ['active', 'completed'])
                ->update(['is_featured' => false]);
        });
    }

    public function scopePublished(Builder $query): void
    {
        $query->whereIn('status', ['active', 'completed']);
    }

    public function scopePreferredForFrontend(Builder $query): void
    {
        $query
            ->published()
            ->orderByDesc('is_featured')
            ->orderByRaw("case when status = 'active' then 1 else 0 end desc")
            ->orderByDesc('end_date')
            ->orderByDesc('start_date')
            ->orderByDesc('id');
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function isPublishedForFrontend(): bool
    {
        return in_array($this->status, ['active', 'completed'], true);
    }

    public function syncCollectedAmount(): void
    {
        $total = $this->donations()
            ->where('payment_status', 'paid')
            ->sum('amount');

        $this->update(['collected_amount' => $total]);
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'campaign_id');
    }

    public function updates(): HasMany
    {
        return $this->hasMany(DonationUpdate::class, 'campaign_id')->orderByDesc('published_at');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function displayTitle(): string
    {
        return (string) ($this->title ?? '');
    }

    public function displayShortDescription(): string
    {
        return (string) ($this->short_description ?? '');
    }

    public function displayDescription(): string
    {
        return (string) ($this->description ?? '');
    }

    public function resolvedBannerImageUrl(): ?string
    {
        return PublicStorageUrl::resolve($this->banner_image_url, verifyPublicDisk: true)
            ?? PublicStorageUrl::fallbackImagePath();
    }
}
