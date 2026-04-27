<?php

namespace App\Support;

class AdminStateOptions
{
    /**
     * @return array<string, string>
     */
    public static function contactMessageStatuses(): array
    {
        return [
            'new' => 'Baru',
            'in_review' => 'Dalam Tinjauan',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function partnershipInquiryStatuses(): array
    {
        return [
            'new' => 'Baru',
            'in_review' => 'Dalam Tinjauan',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function consultationStatuses(): array
    {
        return [
            'new' => 'Baru',
            'in_review' => 'Dalam Tinjauan',
            'scheduled' => 'Terjadwal',
            'resolved' => 'Selesai',
            'closed' => 'Ditutup',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function programStatuses(): array
    {
        return [
            'draft' => 'Draft',
            'published' => 'Terbit',
            'completed' => 'Selesai',
            'archived' => 'Arsip',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function programPhases(): array
    {
        return [
            'active' => 'Aktif',
            'upcoming' => 'Mendatang',
            'archived' => 'Arsip',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function donationCampaignStatuses(): array
    {
        return [
            'draft' => 'Draft',
            'active' => 'Aktif',
            'completed' => 'Selesai',
            'archived' => 'Arsip',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function userStatuses(): array
    {
        return [
            'active' => 'Aktif',
            'inactive' => 'Tidak aktif',
            'suspended' => 'Ditangguhkan',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function donationPaymentStatuses(): array
    {
        return [
            'pending' => 'Menunggu',
            'paid' => 'Lunas',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
        ];
    }

    /**
     * @param  array<string, string>  $options
     */
    public static function labelFor(array $options, ?string $state): string
    {
        return $options[$state] ?? (string) $state;
    }
}
