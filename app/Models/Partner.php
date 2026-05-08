<?php

namespace App\Models;

use App\Support\PublicStorageUrl;
use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Partner extends Model
{
    /** @use HasFactory<PartnerFactory> */
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'logo_url',
        'website_url',
        'type',
        'description',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'program_partners')->withPivot('role');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function resolvedLogoUrl(): ?string
    {
        return PublicStorageUrl::resolve($this->logo_url);
    }
}
