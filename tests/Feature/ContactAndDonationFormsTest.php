<?php

use App\Models\Role;
use App\Models\User;
use App\Support\Notifications\PublicSubmissionReceivedNotification;
use Database\Seeders\GiriFoundationSeeder;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\Queue;

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
        ])->assertTooManyRequests();
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
        ])->assertTooManyRequests();
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
        ])->assertTooManyRequests();
});
