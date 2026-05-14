<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use Database\Factories\OrganizationProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrganizationProfile extends Model
{
    /** @use HasFactory<OrganizationProfileFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'full_description',
        'vision',
        'mission',
        'values',
        'founded_date',
        'email',
        'phone',
        'whatsapp_number',
        'address',
        'google_maps_embed',
        'logo_url',
        'favicon_url',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'founded_date' => 'date',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolvedLogoUrl(): ?string
    {
        return PublicStorageUrl::resolve($this->logo_url, verifyPublicDisk: true);
    }

    public function resolvedFaviconUrl(): ?string
    {
        return PublicStorageUrl::resolve($this->favicon_url, verifyPublicDisk: true);
    }

    public function resolvedGoogleMapsEmbedUrl(): ?string
    {
        $locationSource = trim((string) ($this->google_maps_embed ?: $this->address));

        if ($locationSource === '') {
            return null;
        }

        if (preg_match('/<iframe[^>]+src=["\']([^"\']+)["\']/i', $locationSource, $matches) === 1) {
            return $matches[1];
        }

        if (filter_var($locationSource, FILTER_VALIDATE_URL) !== false) {
            return $locationSource;
        }

        return 'https://www.google.com/maps?q='.rawurlencode(Str::squish($locationSource)).'&output=embed';
    }
}
