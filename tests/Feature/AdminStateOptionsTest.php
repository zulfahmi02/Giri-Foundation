<?php

use App\Support\AdminStateOptions;

test('submission workflow state options stay aligned with admin resources', function () {
    expect(AdminStateOptions::contactMessageStatuses())->toBe([
        'new' => 'Baru',
        'in_review' => 'Dalam Tinjauan',
        'resolved' => 'Selesai',
        'closed' => 'Ditutup',
    ])->and(AdminStateOptions::partnershipInquiryStatuses())->toBe([
        'new' => 'Baru',
        'in_review' => 'Dalam Tinjauan',
        'resolved' => 'Selesai',
        'closed' => 'Ditutup',
    ])->and(AdminStateOptions::consultationStatuses())->toBe([
        'new' => 'Baru',
        'in_review' => 'Dalam Tinjauan',
        'scheduled' => 'Terjadwal',
        'resolved' => 'Selesai',
        'closed' => 'Ditutup',
    ]);
});

test('operational workflow state options stay aligned with admin resources', function () {
    expect(AdminStateOptions::programStatuses())->toBe([
        'draft' => 'Draft',
        'published' => 'Terbit',
        'completed' => 'Selesai',
        'archived' => 'Arsip',
    ])->and(AdminStateOptions::programPhases())->toBe([
        'active' => 'Aktif',
        'upcoming' => 'Mendatang',
        'archived' => 'Arsip',
    ])->and(AdminStateOptions::donationCampaignStatuses())->toBe([
        'draft' => 'Draft',
        'active' => 'Aktif',
        'completed' => 'Selesai',
        'archived' => 'Arsip',
    ])->and(AdminStateOptions::userStatuses())->toBe([
        'active' => 'Aktif',
        'inactive' => 'Tidak aktif',
        'suspended' => 'Ditangguhkan',
    ])->and(AdminStateOptions::donationPaymentStatuses())->toBe([
        'pending' => 'Menunggu',
        'paid' => 'Lunas',
        'failed' => 'Gagal',
        'refunded' => 'Dikembalikan',
    ]);
});
