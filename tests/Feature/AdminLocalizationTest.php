<?php

use App\Filament\Resources\Pages\PageResource;
use App\Filament\Resources\PartnershipInquiries\PartnershipInquiryResource;
use App\Models\Page;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

function createPanelUserForLocalizationTest(): User
{
    $role = Role::query()->firstOrCreate(
        ['name' => 'Admin'],
        ['description' => 'Admin access.'],
    );

    $user = User::factory()->create([
        'app_authentication_secret' => 'totp-secret',
    ]);

    $user->roles()->sync([$role->id]);

    return $user;
}

test('admin generated labels use Indonesian translations', function () {
    expect(config('app.locale'))->toBe('id')
        ->and(TextColumn::make('title')->getLabel())->toBe('Judul')
        ->and(TextColumn::make('slug')->getLabel())->toBe('Alamat URL')
        ->and(TextColumn::make('template')->getLabel())->toBe('Jenis tampilan')
        ->and(TextColumn::make('seo_title')->getLabel())->toBe('Judul SEO')
        ->and(TextColumn::make('published_at')->getLabel())->toBe('Dipublikasikan pada')
        ->and(TextInput::make('password')->getLabel())->toBe('Kata sandi')
        ->and(TextEntry::make('created_at')->getLabel())->toBe('Dibuat pada')
        ->and(SelectFilter::make('status')->getLabel())->toBe('Status');
});

test('admin explicit English labels are translated when rendered', function () {
    expect(TextColumn::make('email')->label('Email address')->getLabel())->toBe('Alamat email')
        ->and(TextEntry::make('author.name')->label('Author')->getLabel())->toBe('Penulis')
        ->and(TextInput::make('campaign_id')->label('Campaign')->getLabel())->toBe('Kampanye')
        ->and(TextInput::make('youtube_url')->label('YouTube URL')->getLabel())->toBe('URL YouTube');
});

test('admin navigation uses Indonesian resource labels', function () {
    expect(PartnershipInquiryResource::getNavigationLabel())->toBe('Permintaan Kemitraan')
        ->and(PartnershipInquiryResource::getModelLabel())->toBe('permintaan kemitraan')
        ->and(PartnershipInquiryResource::getPluralModelLabel())->toBe('Permintaan Kemitraan');
});

test('admin page table renders Indonesian labels', function () {
    $user = createPanelUserForLocalizationTest();

    Page::factory()->create([
        'title' => 'Beranda',
        'slug' => 'home',
        'template' => 'home',
        'status' => 'published',
        'published_at' => now(),
        'created_by' => $user->id,
    ]);

    $this->actingAs($user)
        ->get((string) parse_url(PageResource::getUrl(panel: 'admin'), PHP_URL_PATH))
        ->assertSuccessful()
        ->assertSee('Judul')
        ->assertSee('Judul SEO')
        ->assertSee('Dipublikasikan pada')
        ->assertDontSee('Seo title')
        ->assertDontSee('Published at');
});
