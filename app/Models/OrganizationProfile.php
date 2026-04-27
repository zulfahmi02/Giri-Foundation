<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationProfile extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationProfileFactory> */
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
}
