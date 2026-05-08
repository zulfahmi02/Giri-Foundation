<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use Database\Factories\OrganizationProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return PublicStorageUrl::resolve($this->logo_url);
    }

    public function resolvedFaviconUrl(): ?string
    {
        return PublicStorageUrl::resolve($this->favicon_url);
    }
}
