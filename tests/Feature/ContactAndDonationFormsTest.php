<?php

use App\Models\DonationCampaign;
use App\Models\Role;
use App\Models\User;
use App\Support\Notifications\PublicSubmissionReceivedNotification;
use Database\Seeders\GiriFoundationSeeder;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\Queue;

function createDonationCampaignForFormTest(array $overrides = []): DonationCampaign
{
    return DonationCampaign::query()->create([
        'title' => $overrides['title'] ?? 'Kampanye Donasi '.fake()->unique()->numerify('###'),
        'slug' => $overrides['slug'] ?? fake()->unique()->slug(),
        'short_description' => $overrides['short_description'] ?? 'Ringkasan kampanye donasi untuk pengujian.',
        'description' => $overrides['description'] ?? 'Deskripsi kampanye donasi untuk pengujian fallback dan unggulan.',
        'target_amount' => $overrides['target_amount'] ?? 1000000,
        'collected_amount' => $overrides['collected_amount'] ?? 250000,
        'start_date' => $overrides['start_date'] ?? now()->subDay(),
        'end_date' => $overrides['end_date'] ?? now()->addDays(30),
        'banner_image_url' => $overrides['banner_image_url'] ?? '/image/logo.png',
        'status' => $overrides['status'] ?? 'active',
        'is_featured' => $overrides['is_featured'] ?? false,
        'published_by' => $overrides['published_by'] ?? null,
    ]);
}

test('contact form stores a message', function () {
    $this->seed(GiriFoundationSeeder::class);
    Queue::fake();

    $admin = User::factory()->create([
        'email' => 'admin-contact@example.com',
        'status' => 'active',
    ]);
    $admin->roles()->sync([Role::query()->where('name', 'Admin')->value('id')]);

    $this->post(route('contact.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '+62 811 1000 2000',
        'subject' => 'Field visit request',
        'message' => 'We would like to schedule a site visit and learn more about your community programs.',
    ])->assertRedirect(route('contact.show'));

    $this->assertDatabaseHas('contact_messages', [
        'email' => 'jane@example.com',
        'subject' => 'Field visit request',
        'status' => 'new',
    ]);

    Queue::assertPushed(SendQueuedNotifications::class, function (SendQueuedNotifications $job) use ($admin): bool {
        return $job->connection === 'background'
            && $job->afterCommit === true
            && $job->notification instanceof PublicSubmissionReceivedNotification
            && $job->notifiables->contains(fn ($notifiable): bool => $notifiable instanceof User && $notifiable->is($admin));
    });
});

test('donate page falls back to the newest active campaign when no featured campaign exists', function () {
    createDonationCampaignForFormTest([
        'title' => 'Kampanye Aktif Lama',
        'slug' => 'kampanye-aktif-lama',
        'start_date' => now()->subDays(14),
        'end_date' => now()->addDays(14),
        'status' => 'active',
        'is_featured' => false,
    ]);

    $newestActiveCampaign = createDonationCampaignForFormTest([
        'title' => 'Kampanye Aktif Terbaru',
        'slug' => 'kampanye-aktif-terbaru',
        'start_date' => now()->subDays(2),
        'end_date' => now()->addDays(21),
        'status' => 'active',
        'is_featured' => false,
    ]);

    createDonationCampaignForFormTest([
        'title' => 'Kampanye Selesai Terbaru',
        'slug' => 'kampanye-selesai-terbaru',
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
        'status' => 'completed',
        'is_featured' => false,
    ]);

    $this->get(route('donate.show'))
        ->assertSuccessful()
        ->assertSee($newestActiveCampaign->title);
});

test('marking a published campaign as featured demotes the previous featured campaign', function () {
    $previousFeaturedCampaign = createDonationCampaignForFormTest([
        'title' => 'Kampanye Unggulan Lama',
        'slug' => 'kampanye-unggulan-lama',
        'is_featured' => true,
    ]);

    $replacementCampaign = createDonationCampaignForFormTest([
        'title' => 'Kampanye Unggulan Baru',
        'slug' => 'kampanye-unggulan-baru',
        'is_featured' => false,
    ]);

    $replacementCampaign->update([
        'is_featured' => true,
    ]);

    expect($previousFeaturedCampaign->fresh()->is_featured)->toBeFalse()
        ->and($replacementCampaign->fresh()->is_featured)->toBeTrue();
});

test('partnership inquiry stores a record', function () {
    $this->seed(GiriFoundationSeeder::class);
    Queue::fake();

    $admin = User::factory()->create([
        'email' => 'admin-partner@example.com',
        'status' => 'active',
    ]);
    $admin->roles()->sync([Role::query()->where('name', 'Admin')->value('id')]);

    $this->post(route('partners.store'), [
        'organization_name' => 'Northern Trust',
        'contact_person' => 'Rafi Senja',
        'email' => 'rafi@northerntrust.example',
        'phone' => '+62 811 3333 4444',
        'inquiry_type' => 'Strategic funding',
        'message' => 'We are interested in discussing a three-year funding partnership focused on education programs.',
    ])->assertRedirect(route('partners.index'));

    $this->assertDatabaseHas('partnership_inquiries', [
        'organization_name' => 'Northern Trust',
        'status' => 'new',
    ]);

    Queue::assertPushed(SendQueuedNotifications::class, function (SendQueuedNotifications $job) use ($admin): bool {
        return $job->connection === 'background'
            && $job->afterCommit === true
            && $job->notification instanceof PublicSubmissionReceivedNotification
            && $job->notifiables->contains(fn ($notifiable): bool => $notifiable instanceof User && $notifiable->is($admin));
    });
});

test('donation form stores donor and donation intent', function () {
    $this->seed(GiriFoundationSeeder::class);
    Queue::fake();

    $admin = User::factory()->create([
        'email' => 'admin-donation@example.com',
        'status' => 'active',
    ]);
    $admin->roles()->sync([Role::query()->where('name', 'Admin')->value('id')]);

    $this->post(route('donate.store'), [
        'full_name' => 'Ayu Lestari',
        'email' => 'ayu@example.com',
        'phone' => '+62 812 9000 1000',
        'amount' => 250,
        'payment_method' => 'bank_transfer',
        'payment_channel' => 'manual',
        'message' => 'For the school solar campaign.',
        'is_anonymous' => '0',
    ])->assertRedirect(route('donate.show'));

    $this->assertDatabaseHas('donors', [
        'email' => 'ayu@example.com',
        'full_name' => 'Ayu Lestari',
    ]);

    $this->assertDatabaseHas('donations', [
        'amount' => 250,
        'payment_method' => 'bank_transfer',
        'payment_status' => 'pending',
    ]);

    Queue::assertPushed(SendQueuedNotifications::class, function (SendQueuedNotifications $job) use ($admin): bool {
        return $job->connection === 'background'
            && $job->afterCommit === true
            && $job->notification instanceof PublicSubmissionReceivedNotification
            && $job->notifiables->contains(fn ($notifiable): bool => $notifiable instanceof User && $notifiable->is($admin));
    });
});

test('contact form is rate limited after five submissions per minute', function () {
    $this->seed(GiriFoundationSeeder::class);
    Queue::fake();

    for ($attempt = 1; $attempt <= 5; $attempt++) {
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.11'])
            ->post(route('contact.store'), [
                'name' => 'Jane Doe',
                'email' => "jane{$attempt}@example.com",
                'phone' => '+62 811 1000 2000',
                'subject' => "Field visit request {$attempt}",
                'message' => 'We would like to schedule a site visit and learn more about your community programs.',
            ])->assertRedirect(route('contact.show'));
    }

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.11'])
        ->post(route('contact.store'), [
            'name' => 'Jane Doe',
            'email' => 'jane-over-limit@example.com',
            'phone' => '+62 811 1000 2000',
            'subject' => 'Field visit request over limit',
            'message' => 'We would like to schedule a site visit and learn more about your community programs.',
        ])
        ->assertRedirect(route('contact.show'))
        ->assertSessionHasErrors(['form']);
});

test('partnership inquiry form is rate limited after five submissions per minute', function () {
    $this->seed(GiriFoundationSeeder::class);
    Queue::fake();

    for ($attempt = 1; $attempt <= 5; $attempt++) {
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.12'])
            ->post(route('partners.store'), [
                'organization_name' => "Northern Trust {$attempt}",
                'contact_person' => 'Rafi Senja',
                'email' => "rafi{$attempt}@northerntrust.example",
                'phone' => '+62 811 3333 4444',
                'inquiry_type' => 'Strategic funding',
                'message' => 'We are interested in discussing a three-year funding partnership focused on education programs.',
            ])->assertRedirect(route('partners.index'));
    }

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.12'])
        ->post(route('partners.store'), [
            'organization_name' => 'Northern Trust Over Limit',
            'contact_person' => 'Rafi Senja',
            'email' => 'rafi-over-limit@northerntrust.example',
            'phone' => '+62 811 3333 4444',
            'inquiry_type' => 'Strategic funding',
            'message' => 'We are interested in discussing a three-year funding partnership focused on education programs.',
        ])
        ->assertRedirect(route('partners.index'))
        ->assertSessionHasErrors(['form']);
});

test('donation form is rate limited after five submissions per minute', function () {
    $this->seed(GiriFoundationSeeder::class);
    Queue::fake();

    for ($attempt = 1; $attempt <= 5; $attempt++) {
        $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.13'])
            ->post(route('donate.store'), [
                'full_name' => 'Ayu Lestari',
                'email' => "ayu{$attempt}@example.com",
                'phone' => '+62 812 9000 1000',
                'amount' => 250,
                'payment_method' => 'bank_transfer',
                'payment_channel' => 'manual',
                'message' => 'For the school solar campaign.',
                'is_anonymous' => '0',
            ])->assertRedirect(route('donate.show'));
    }

    $this->withServerVariables(['REMOTE_ADDR' => '127.0.0.13'])
        ->post(route('donate.store'), [
            'full_name' => 'Ayu Lestari',
            'email' => 'ayu-over-limit@example.com',
            'phone' => '+62 812 9000 1000',
            'amount' => 250,
            'payment_method' => 'bank_transfer',
            'payment_channel' => 'manual',
            'message' => 'For the school solar campaign.',
            'is_anonymous' => '0',
        ])
        ->assertRedirect(route('donate.show'))
        ->assertSessionHasErrors(['form']);
});

test('contact page renders form feedback and centered contact cards', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->followingRedirects()
        ->from(route('contact.show'))
        ->post(route('contact.store'), [
            'name' => 'Jane Doe',
            'email' => 'invalid-email',
            'phone' => '+62 811 1000 2000',
            'subject' => 'Short inquiry',
            'message' => 'Too short',
        ])
        ->assertSuccessful()
        ->assertSee('Mohon periksa kembali form kontak Anda.')
        ->assertSee('Kolom email harus berupa alamat email yang valid.')
        ->assertSee('data-submit-feedback-form', false)
        ->assertSee('data-submit-feedback-button', false)
        ->assertSee('Kirim Pesan')
        ->assertSee('Mengirim...')
        ->assertSee('animate-spin', false)
        ->assertSee('justify-items-center gap-8 px-6 md:grid-cols-2 lg:grid-cols-3', false)
        ->assertSee('flex w-full max-w-sm flex-col items-center rounded-[1.75rem] p-8 text-center', false);
});

test('contact submit loading state is wired through the public javascript bundle', function () {
    expect(file_get_contents(resource_path('js/app.js')))
        ->toContain('initializeSubmitFeedbackForms')
        ->toContain('[data-submit-feedback-form]')
        ->toContain('[data-submit-feedback-button]')
        ->toContain('aria-busy');
});
