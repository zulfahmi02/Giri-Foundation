<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonationCampaign extends Model
{
    /** @use HasFactory<\Database\Factories\DonationCampaignFactory> */
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

    public function scopePublished(Builder $query): void
    {
        $query->whereIn('status', ['active', 'completed']);
    }

    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
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
        return $this->translatedCampaignValue('title');
    }

    public function displayShortDescription(): string
    {
        return $this->translatedCampaignValue('short_description');
    }

    public function displayDescription(): string
    {
        return $this->translatedCampaignValue('description');
    }

    private function translatedCampaignValue(string $attribute): string
    {
        $value = (string) ($this->{$attribute} ?? '');

        if (! app()->isLocale('id')) {
            return $value;
        }

        $translation = $this->campaignTranslationMap()[$this->slug][$attribute] ?? null;

        if (! is_array($translation)) {
            return $value;
        }

        return $value === $translation['english'] ? $translation['indonesian'] : $value;
    }

    /**
     * @return array<string, array<string, array{english: string, indonesian: string}>>
     */
    private function campaignTranslationMap(): array
    {
        return [
            'solar-power-for-giri-central-school' => [
                'title' => [
                    'english' => 'Solar Power for GIRI Central School',
                    'indonesian' => 'Tenaga Surya untuk Sekolah GIRI Central',
                ],
                'short_description' => [
                    'english' => 'Help fund a resilient energy system for a remote learning center.',
                    'indonesian' => 'Bantu mendanai sistem energi tangguh untuk pusat belajar di wilayah terpencil.',
                ],
                'description' => [
                    'english' => 'This campaign equips GIRI Central School with reliable solar power so classrooms, digital labs, and evening study programs can operate without disruption.',
                    'indonesian' => 'Kampanye ini membekali Sekolah GIRI Central dengan tenaga surya yang andal agar ruang kelas, laboratorium digital, dan program belajar malam dapat berjalan tanpa gangguan.',
                ],
            ],
        ];
    }
}
