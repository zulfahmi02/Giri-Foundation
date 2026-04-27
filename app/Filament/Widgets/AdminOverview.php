<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Contents\ContentResource;
use App\Filament\Resources\DonationCampaigns\DonationCampaignResource;
use App\Filament\Resources\Donations\DonationResource;
use App\Filament\Resources\Programs\ProgramResource;
use App\Models\Consultation;
use App\Models\ContactMessage;
use App\Models\Content;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\PartnershipInquiry;
use App\Models\Program;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = false;

    protected ?string $heading = 'Ringkasan Operasional';

    protected ?string $description = 'Akses cepat ke pekerjaan yang paling sering ditindaklanjuti oleh tim admin.';

    protected ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $activePrograms = Program::query()
            ->where('status', 'published')
            ->where('phase', 'active')
            ->count();

        $draftContents = Content::query()
            ->where('status', 'draft')
            ->count();

        $newInboxItems = ContactMessage::query()->where('status', 'new')->count()
            + PartnershipInquiry::query()->where('status', 'new')->count()
            + Consultation::query()->where('status', 'new')->count();

        $pendingDonations = Donation::query()
            ->where('payment_status', 'pending')
            ->count();

        $activeCampaigns = DonationCampaign::query()
            ->where('status', 'active')
            ->count();

        return [
            Stat::make('Program Aktif', $activePrograms)
                ->description('Program berjalan yang perlu dipantau')
                ->color('success')
                ->url(ProgramResource::getUrl(panel: 'admin')),
            Stat::make('Draft Editorial', $draftContents)
                ->description('Konten yang belum diterbitkan')
                ->color($draftContents > 0 ? 'warning' : 'gray')
                ->url(ContentResource::getUrl(panel: 'admin')),
            Stat::make('Inbox Baru', $newInboxItems)
                ->description('Pesan, inquiry, dan konsultasi belum diproses')
                ->color($newInboxItems > 0 ? 'danger' : 'gray')
                ->url(ContactMessageResource::getUrl(panel: 'admin')),
            Stat::make('Donasi Menunggu', $pendingDonations)
                ->description('Transaksi yang masih perlu verifikasi')
                ->color($pendingDonations > 0 ? 'warning' : 'gray')
                ->url(DonationResource::getUrl(panel: 'admin')),
            Stat::make('Kampanye Aktif', $activeCampaigns)
                ->description('Kampanye donasi yang sedang berjalan')
                ->color('info')
                ->url(DonationCampaignResource::getUrl(panel: 'admin')),
        ];
    }
}
