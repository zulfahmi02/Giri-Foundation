<?php

namespace App\Filament\Clusters\Contact;

use App\Models\Consultation;
use App\Models\ContactMessage;
use App\Models\PartnershipInquiry;
use BackedEnum;
use Illuminate\Support\Facades\Cache;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class ContactCluster extends Cluster
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Kontak';

    protected static ?int $navigationSort = 60;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationBadge(): ?string
    {
        $pendingInboxItems = Cache::remember('nav_badge_contact_messages_new', 60, fn () => ContactMessage::query()->where('status', 'new')->count())
            + Cache::remember('nav_badge_partnership_inquiries_new', 60, fn () => PartnershipInquiry::query()->where('status', 'new')->count())
            + Cache::remember('nav_badge_consultations_new', 60, fn () => Consultation::query()->where('status', 'new')->count());

        return $pendingInboxItems > 0 ? (string) $pendingInboxItems : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }
}
