<?php

use App\Filament\Resources\Activities\Schemas\ActivityForm;
use App\Filament\Resources\Activities\Schemas\ActivityInfolist;
use App\Filament\Resources\Contents\Schemas\ContentForm;
use App\Filament\Resources\Contents\Schemas\ContentInfolist;
use App\Filament\Resources\Documents\Schemas\DocumentForm;
use App\Filament\Resources\Documents\Schemas\DocumentInfolist;
use App\Filament\Resources\DonationCampaigns\Schemas\DonationCampaignForm;
use App\Filament\Resources\DonationCampaigns\Schemas\DonationCampaignInfolist;
use App\Filament\Resources\DonationUpdates\Schemas\DonationUpdateForm;
use App\Filament\Resources\DonationUpdates\Schemas\DonationUpdateInfolist;
use App\Filament\Resources\OrganizationProfiles\Schemas\OrganizationProfileForm;
use App\Filament\Resources\OrganizationProfiles\Schemas\OrganizationProfileInfolist;
use App\Filament\Resources\Partners\Schemas\PartnerForm;
use App\Filament\Resources\Partners\Schemas\PartnerInfolist;
use App\Filament\Resources\Programs\Schemas\ProgramForm;
use App\Filament\Resources\Programs\Schemas\ProgramInfolist;
use App\Filament\Resources\TeamMembers\Schemas\TeamMemberInfolist;
use App\Filament\Resources\Videos\Schemas\VideoForm;
use App\Filament\Resources\Videos\Schemas\VideoInfolist;
use App\Models\Activity;
use App\Models\Content;
use App\Models\Document;
use App\Models\DonationCampaign;
use App\Models\DonationUpdate;
use App\Models\OrganizationProfile;
use App\Models\Partner;
use App\Models\Program;
use App\Models\ProgramGallery;
use App\Models\Video;
use App\Support\PublicStorageUrl;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Schema;

it('uses device file uploads for admin image fields', function (string $formClass, string $field, string $directory): void {
    $schema = $formClass::configure(Schema::make());

    $component = $schema->getComponentByStatePath($field, withHidden: true);

    expect($component)->toBeInstanceOf(FileUpload::class)
        ->and($component->getDiskName())->toBe('public')
        ->and($component->getDirectory())->toBe($directory)
        ->and($component->getVisibility())->toBe('public')
        ->and($component->shouldFetchFileInformation())->toBeFalse()
        ->and($component->getAcceptedFileTypes())->toBe(['image/jpeg', 'image/png', 'image/webp']);
})->with([
    'program featured image' => [ProgramForm::class, 'featured_image_url', 'programs'],
    'activity featured image' => [ActivityForm::class, 'featured_image_url', 'activities'],
    'content cover image' => [ContentForm::class, 'featured_image_url', 'contents'],
    'donation campaign banner' => [DonationCampaignForm::class, 'banner_image_url', 'donation-campaigns'],
    'donation update image' => [DonationUpdateForm::class, 'image_url', 'donation-updates'],
    'partner logo' => [PartnerForm::class, 'logo_url', 'partners'],
    'video thumbnail' => [VideoForm::class, 'thumbnail_url', 'videos'],
    'organization logo' => [OrganizationProfileForm::class, 'logo_url', 'organization'],
    'organization favicon' => [OrganizationProfileForm::class, 'favicon_url', 'organization'],
    'document thumbnail' => [DocumentForm::class, 'thumbnail_url', 'documents/thumbnails'],
]);

it('renders uploaded images as previews in admin detail pages', function (string $infolistClass, string $field): void {
    $schema = $infolistClass::configure(Schema::make());

    expect($schema->getComponentByStatePath($field, withHidden: true))->toBeInstanceOf(ImageEntry::class);
})->with([
    'program featured image' => [ProgramInfolist::class, 'featured_image_url'],
    'activity featured image' => [ActivityInfolist::class, 'featured_image_url'],
    'content cover image' => [ContentInfolist::class, 'featured_image_url'],
    'donation campaign banner' => [DonationCampaignInfolist::class, 'banner_image_url'],
    'donation update image' => [DonationUpdateInfolist::class, 'image_url'],
    'partner logo' => [PartnerInfolist::class, 'logo_url'],
    'video thumbnail' => [VideoInfolist::class, 'thumbnail_url'],
    'organization logo' => [OrganizationProfileInfolist::class, 'logo_url'],
    'organization favicon' => [OrganizationProfileInfolist::class, 'favicon_url'],
    'document thumbnail' => [DocumentInfolist::class, 'thumbnail_url'],
    'team member photo' => [TeamMemberInfolist::class, 'photo_url'],
]);

it('resolves uploaded image paths while preserving legacy urls', function (): void {
    expect(PublicStorageUrl::resolve('programs/kegiatan.jpg'))->toBe('/storage/programs/kegiatan.jpg')
        ->and(PublicStorageUrl::resolve('/image/logo.png'))->toBe('/image/logo.png')
        ->and(PublicStorageUrl::resolve('image/logo.png'))->toBe('/image/logo.png')
        ->and(PublicStorageUrl::resolve('storage/partners/logo.png'))->toBe('/storage/partners/logo.png')
        ->and(PublicStorageUrl::resolve('/storage/partners/logo.png'))->toBe('/storage/partners/logo.png')
        ->and(PublicStorageUrl::resolve('public/programs/kegiatan.jpg'))->toBe('/storage/programs/kegiatan.jpg')
        ->and(PublicStorageUrl::resolve('storage/app/public/programs/kegiatan.jpg'))->toBe('/storage/programs/kegiatan.jpg')
        ->and(PublicStorageUrl::resolve('https://example.com/image.jpg'))->toBe('https://example.com/image.jpg')
        ->and(PublicStorageUrl::resolve('#'))->toBeNull();
});

it('exposes public image urls from content models', function (): void {
    expect((new Program(['featured_image_url' => 'programs/unggulan.jpg']))->resolvedFeaturedImageUrl())->toBe('/storage/programs/unggulan.jpg')
        ->and((new Activity(['featured_image_url' => 'activities/foto.jpg']))->resolvedFeaturedImageUrl())->toBe('/storage/activities/foto.jpg')
        ->and((new Content(['featured_image_url' => 'contents/cerita.jpg']))->resolvedFeaturedImageUrl())->toBe('/storage/contents/cerita.jpg')
        ->and((new DonationCampaign(['banner_image_url' => 'donation-campaigns/banner.jpg']))->resolvedBannerImageUrl())->toBe('/storage/donation-campaigns/banner.jpg')
        ->and((new DonationUpdate(['image_url' => 'donation-updates/update.jpg']))->resolvedImageUrl())->toBe('/storage/donation-updates/update.jpg')
        ->and((new Partner(['logo_url' => 'partners/logo.jpg']))->resolvedLogoUrl())->toBe('/storage/partners/logo.jpg')
        ->and((new OrganizationProfile(['logo_url' => 'organization/logo.jpg']))->resolvedLogoUrl())->toBe('/storage/organization/logo.jpg')
        ->and((new OrganizationProfile(['favicon_url' => 'organization/favicon.png']))->resolvedFaviconUrl())->toBe('/storage/organization/favicon.png')
        ->and((new ProgramGallery(['file_url' => 'programs/gallery.jpg']))->resolvedFileUrl())->toBe('/storage/programs/gallery.jpg')
        ->and((new Document(['thumbnail_url' => 'documents/thumbnails/cover.jpg']))->resolvedThumbnailUrl())->toBe('/storage/documents/thumbnails/cover.jpg')
        ->and((new Video([
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'thumbnail_url' => 'videos/thumb.jpg',
        ]))->resolvedThumbnailUrl())->toBe('/storage/videos/thumb.jpg');
});
