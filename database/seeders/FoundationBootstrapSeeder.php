<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FoundationBootstrapSeeder extends Seeder
{
    public function run(): void
    {
        $this->retireLegacyDefaultAdmin();

        $adminRole = Role::query()->updateOrCreate(
            ['name' => 'Admin'],
            ['description' => 'Mengelola akses panel, konfigurasi sistem, audit, dan data donasi.'],
        );

        Role::query()->updateOrCreate(
            ['name' => 'Editor'],
            ['description' => 'Mengelola konten, program, dokumen, dan informasi publik yayasan.'],
        );

        $this->seedInitialAdmin($adminRole);
    }

    private function retireLegacyDefaultAdmin(): void
    {
        $legacyAdmin = User::query()
            ->where('email', 'admin@giri.foundation')
            ->first();

        if (! $legacyAdmin || blank($legacyAdmin->password_hash) || (! Hash::check('password', $legacyAdmin->password_hash))) {
            return;
        }

        $legacyAdmin->roles()->detach();
        $legacyAdmin->forceFill([
            'name' => 'Legacy Disabled Admin',
            'password' => Str::random(40),
            'status' => 'inactive',
            'last_login_at' => null,
            'email_verified_at' => null,
        ])->save();
    }

    private function seedInitialAdmin(Role $adminRole): void
    {
        $name = env('FILAMENT_ADMIN_NAME');
        $email = env('FILAMENT_ADMIN_EMAIL');
        $password = env('FILAMENT_ADMIN_PASSWORD');

        if (blank($name) || blank($email) || blank($password)) {
            return;
        }

        $admin = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'phone' => null,
                'status' => 'active',
                'avatar_url' => null,
                'email_verified_at' => now(),
            ],
        );

        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
    }
}
