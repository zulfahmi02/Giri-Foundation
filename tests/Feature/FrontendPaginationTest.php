<?php

use App\Models\Activity;
use App\Models\Content;
use App\Models\Document;
use App\Models\Program;
use App\Models\Video;
use Database\Seeders\GiriFoundationSeeder;
use Illuminate\Support\Str;

test('media page uses separate paginator query parameters', function () {
    $this->seed(GiriFoundationSeeder::class);

    $program = Program::query()->firstOrFail();

    foreach (range(1, 8) as $index) {
        Activity::query()->create([
            'program_id' => $program->id,
            'title' => "Aktivitas paginasi {$index}",
            'slug' => "aktivitas-paginasi-{$index}",
            'summary' => "Ringkasan aktivitas {$index}",
            'description' => "Deskripsi aktivitas {$index}",
            'activity_date' => now()->subDays($index),
            'location_name' => 'Bojonegoro',
            'featured_image_url' => 'https://example.com/activity.jpg',
            'status' => 'published',
            'published_at' => now()->subDays($index),
        ]);
    }

    foreach (range(1, 5) as $index) {
        Video::query()->create([
            'title' => "Video paginasi {$index}",
            'slug' => "video-paginasi-{$index}",
            'summary' => "Ringkasan video {$index}",
            'description' => "Deskripsi video {$index}",
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'thumbnail_url' => 'https://example.com/video.jpg',
            'sort_order' => 100 + $index,
            'status' => 'published',
            'published_at' => now()->subDays($index),
        ]);
    }

    $this->get('/media')
        ->assertSuccessful()
        ->assertDontSee('Aktivitas paginasi 8')
        ->assertDontSee('Video paginasi 5');

    $this->get('/media?activities_page=2')
        ->assertSuccessful()
        ->assertSee('Aktivitas paginasi 8')
        ->assertDontSee('Video paginasi 5');

    $this->get('/media?videos_page=2')
        ->assertSuccessful()
        ->assertSee('Video paginasi 5')
        ->assertDontSee('Aktivitas paginasi 8');
});

test('resources page paginates long document lists', function () {
    $this->seed(GiriFoundationSeeder::class);

    foreach (range(1, 11) as $index) {
        Document::query()->create([
            'title' => "Dokumen paginasi {$index}",
            'slug' => "dokumen-paginasi-{$index}",
            'category' => 'Laporan',
            'description' => "Deskripsi dokumen {$index}",
            'file_url' => 'https://example.com/document.pdf',
            'thumbnail_url' => null,
            'file_type' => 'PDF',
            'file_size' => 1024,
            'download_count' => 0,
            'is_public' => true,
            'published_at' => now()->subDays($index),
        ]);
    }

    $this->get('/resources')
        ->assertSuccessful()
        ->assertDontSee('Dokumen paginasi 11');

    $this->get('/resources?page=2')
        ->assertSuccessful()
        ->assertDontSee('Dokumen paginasi 11');

    $this->get('/resources?documents_page=2')
        ->assertSuccessful()
        ->assertSee('Dokumen paginasi 11');
});

test('stories page paginates archive stories after featured and secondary stories', function () {
    $this->seed(GiriFoundationSeeder::class);

    foreach (range(1, 10) as $index) {
        Content::query()->create([
            'title' => "Cerita tambahan {$index}",
            'slug' => Str::slug("cerita-tambahan-{$index}"),
            'type' => 'story',
            'category_id' => null,
            'excerpt' => "Ringkasan cerita {$index}",
            'body' => "Isi cerita {$index}",
            'featured_image_url' => 'https://example.com/story.jpg',
            'author_id' => null,
            'status' => 'published',
            'is_featured' => false,
            'published_at' => now()->subDays($index),
            'seo_title' => null,
            'seo_description' => null,
        ]);
    }

    $this->get('/stories')
        ->assertSuccessful()
        ->assertDontSee('Cerita tambahan 10');

    $this->get('/stories?page=2')
        ->assertSuccessful()
        ->assertDontSee('Cerita tambahan 10');

    $this->get('/stories?stories_page=2')
        ->assertSuccessful()
        ->assertSee('Cerita tambahan 10');
});
