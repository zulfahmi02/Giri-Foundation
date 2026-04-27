<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthentication;
use Filament\Auth\MultiFactor\App\Concerns\InteractsWithAppAuthenticationRecovery;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
    use InteractsWithAppAuthentication;
    use InteractsWithAppAuthenticationRecovery;
    use Notifiable;
    use SoftDeletes;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'password_hash',
        'phone',
        'avatar_url',
        'status',
        'last_login_at',
        'email_verified_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'password_hash',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password_hash' => 'hashed',
        ];
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value): array => [
                'password_hash' => filled($value)
                    ? ($this->appearsToBeHashedPassword($value) ? $value : Hash::make($value))
                    : null,
            ],
        );
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function programs(): HasMany
    {
        return $this->hasMany(Program::class, 'created_by');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(Content::class, 'author_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() !== 'admin') {
            return true;
        }

        return $this->hasPanelAccess();
    }

    public function hasPanelAccess(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if (app()->isLocal()) {
            return true;
        }

        return $this->hasAnyRole(['Admin', 'Editor']);
    }

    public function hasRole(string $roleName): bool
    {
        return $this->hasAnyRole([$roleName]);
    }

    /**
     * @param  list<string>  $roleNames
     */
    public function hasAnyRole(array $roleNames): bool
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles
                ->pluck('name')
                ->intersect($roleNames)
                ->isNotEmpty();
        }

        return $this->roles()
            ->whereIn('name', $roleNames)
            ->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    protected function appearsToBeHashedPassword(string $value): bool
    {
        return password_get_info($value)['algo'] !== null;
    }
}
