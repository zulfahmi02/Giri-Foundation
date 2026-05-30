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
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminOverview extends StatsOverviewWidget
{
    protected static bool $isLazy = true;

    protected static ?int $sort = 1;

    protected ?string $heading = 'Ringkasan Operasional';

    protected ?string $description = 'Kondisi terkini website GIRI Foundation. Klik kartu untuk langsung ke halamannya.';

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

        $newContactMessages = ContactMessage::query()->where('status', 'new')->count();
        $newPartnershipInquiries = PartnershipInquiry::query()->where('status', 'new')->count();
        $newConsultations = Consultation::query()->where('status', 'new')->count();
        $newInboxItems = $newContactMessages + $newPartnershipInquiries + $newConsultations;

        $pendingDonations = Donation::query()
            ->where('payment_status', 'pending')
            ->count();

        $activeCampaigns = DonationCampaign::query()
            ->where('status', 'active')
            ->count();

        $totalDonasiBulanIni = (float) Donation::query()
            ->where('payment_status', 'paid')
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('amount');

        return [
            Stat::make('Donasi Terkumpul Bulan Ini', 'Rp ' . number_format($totalDonasiBulanIni, 0, ',', '.'))
                ->description('Total donasi terkonfirmasi bulan ' . now()->locale('id')->isoFormat('MMMM YYYY'))
                ->icon(Heroicon::OutlinedBanknotes)
                ->color($totalDonasiBulanIni > 0 ? 'success' : 'gray')
                ->url(DonationResource::getUrl(panel: 'admin')),

            Stat::make('Inbox Baru', $newInboxItems)
                ->description(
                    $newInboxItems > 0
                        ? "Pesan: {$newContactMessages} · Konsultasi: {$newConsultations} · Kemitraan: {$newPartnershipInquiries}"
                        : 'Semua pesan sudah ditangani'
                )
                ->icon(Heroicon::OutlinedInbox)
                ->color($newInboxItems > 0 ? 'danger' : 'success')
                ->url(ContactMessageResource::getUrl(panel: 'admin')),

            Stat::make('Donasi Menunggu Verifikasi', $pendingDonations)
                ->description($pendingDonations > 0 ? 'Perlu dikonfirmasi pembayarannya' : 'Tidak ada donasi menunggu')
                ->icon(Heroicon::OutlinedClock)
                ->color($pendingDonations > 0 ? 'warning' : 'gray')
                ->url(DonationResource::getUrl(panel: 'admin')),

            Stat::make('Program Aktif', $activePrograms)
                ->description($activePrograms > 0 ? 'Program yang sedang berjalan' : 'Belum ada program aktif')
                ->icon(Heroicon::OutlinedRocketLaunch)
                ->color($activePrograms > 0 ? 'success' : 'gray')
                ->url(ProgramResource::getUrl(panel: 'admin')),

            Stat::make('Konten Belum Terbit', $draftContents)
                ->description($draftContents > 0 ? 'Masih dalam status draft, belum tampil di website' : 'Semua konten sudah diterbitkan')
                ->icon(Heroicon::OutlinedDocumentText)
                ->color($draftContents > 0 ? 'warning' : 'gray')
                ->url(ContentResource::getUrl(panel: 'admin')),

            Stat::make('Kampanye Donasi Aktif', $activeCampaigns)
                ->description($activeCampaigns > 0 ? 'Sedang menerima donasi dari publik' : 'Tidak ada kampanye aktif')
                ->icon(Heroicon::OutlinedHeart)
                ->color($activeCampaigns > 0 ? 'info' : 'gray')
                ->url(DonationCampaignResource::getUrl(panel: 'admin')),
        ];
    }
}
