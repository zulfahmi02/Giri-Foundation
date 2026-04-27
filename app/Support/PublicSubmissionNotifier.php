<?php

namespace App\Support;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Resources\Donations\DonationResource;
use App\Filament\Resources\PartnershipInquiries\PartnershipInquiryResource;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\PartnershipInquiry;
use App\Support\Notifications\PublicSubmissionReceivedNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class PublicSubmissionNotifier
{
    public function sendContactMessage(ContactMessage $contactMessage): void
    {
        $this->send(new PublicSubmissionReceivedNotification(
            subjectLine: 'Pesan kontak baru diterima',
            headline: "Pesan kontak baru dari {$contactMessage->name}.",
            details: [
                "Subjek: {$contactMessage->subject}",
                "Email pengirim: {$contactMessage->email}",
            ],
            reviewUrl: ContactMessageResource::getUrl('view', ['record' => $contactMessage]),
        ));
    }

    public function sendPartnershipInquiry(PartnershipInquiry $partnershipInquiry): void
    {
        $this->send(new PublicSubmissionReceivedNotification(
            subjectLine: 'Inquiry kemitraan baru diterima',
            headline: "Inquiry kemitraan baru dari {$partnershipInquiry->organization_name}.",
            details: [
                "Kontak utama: {$partnershipInquiry->contact_person}",
                "Jenis inquiry: {$partnershipInquiry->inquiry_type}",
            ],
            reviewUrl: PartnershipInquiryResource::getUrl('view', ['record' => $partnershipInquiry]),
        ));
    }

    public function sendDonationIntent(Donation $donation): void
    {
        $donation->loadMissing(['campaign', 'donor']);

        $donorName = $donation->donor?->is_anonymous
            ? 'Donatur anonim'
            : ($donation->donor?->full_name ?? 'Donatur baru');

        $formattedAmount = Number::format(
            (float) $donation->amount,
            maxPrecision: 2,
            locale: app()->getLocale(),
        );

        $this->send(new PublicSubmissionReceivedNotification(
            subjectLine: 'Minat donasi baru diterima',
            headline: "Minat donasi baru dari {$donorName}.",
            details: [
                "Nominal: {$formattedAmount}",
                'Metode pembayaran: ' . Str::headline($donation->payment_method),
                'Kampanye: ' . ($donation->campaign?->title ?? 'Tanpa kampanye'),
            ],
            reviewUrl: DonationResource::getUrl('view', ['record' => $donation]),
        ));
    }

    private function send(PublicSubmissionReceivedNotification $notification): void
    {
        $recipients = AdminNotificationRecipients::forPublicSubmissions();

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, $notification);

            return;
        }

        $fallbackEmail = AdminNotificationRecipients::fallbackEmail();

        if (blank($fallbackEmail)) {
            return;
        }

        Notification::route('mail', $fallbackEmail)->notify($notification);
    }
}
