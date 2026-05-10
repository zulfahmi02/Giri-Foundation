<?php

use App\Filament\Resources\Activities\Schemas\ActivityForm;
use App\Filament\Resources\ContentCategories\Schemas\ContentCategoryForm;
use App\Filament\Resources\Contents\Schemas\ContentForm;
use App\Filament\Resources\Divisions\Schemas\DivisionForm;
use App\Filament\Resources\Documents\Schemas\DocumentForm;
use App\Filament\Resources\DonationCampaigns\Schemas\DonationCampaignForm;
use App\Filament\Resources\OrganizationProfiles\Schemas\OrganizationProfileForm;
use App\Filament\Resources\Partners\Schemas\PartnerForm;
use App\Filament\Resources\ProgramCategories\Schemas\ProgramCategoryForm;
use App\Filament\Resources\Programs\Schemas\ProgramForm;
use App\Filament\Resources\Videos\Schemas\VideoForm;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

it('makes admin slug fields automatic and read only', function (string $formClass): void {
    $schema = $formClass::configure(Schema::make());

    $component = $schema->getComponentByStatePath('slug', withHidden: true);

    expect($component)->toBeInstanceOf(TextInput::class)
        ->and($component->getLabel())->toBe('Alamat URL')
        ->and($component->isReadOnly())->toBeTrue();
})->with([
    'program' => [ProgramForm::class],
    'program category' => [ProgramCategoryForm::class],
    'activity' => [ActivityForm::class],
    'video' => [VideoForm::class],
    'content' => [ContentForm::class],
    'content category' => [ContentCategoryForm::class],
    'document' => [DocumentForm::class],
    'partner' => [PartnerForm::class],
    'division' => [DivisionForm::class],
    'organization profile' => [OrganizationProfileForm::class],
    'donation campaign' => [DonationCampaignForm::class],
]);
