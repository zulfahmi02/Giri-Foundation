<?php

use App\Models\Content;
use App\Models\Program;
use Database\Seeders\GiriFoundationSeeder;

test('home page exposes verification, canonical, and structured data metadata', function () {
    config()->set('seo.google_site_verification', 'gsc-verification-token');

    $this->seed(GiriFoundationSeeder::class);

    $this->get('/')
        ->assertSuccessful()
        ->assertSee('name="google-site-verification" content="gsc-verification-token"', false)
        ->assertSee('<link rel="canonical" href="' . route('home') . '">', false)
        ->assertSee('property="og:type" content="website"', false)
        ->assertSee('"@type":"Organization"', false)
        ->assertSee('"@type":"WebSite"', false);
});

test('resource search pages are marked as noindex', function () {
    $this->seed(GiriFoundationSeeder::class);

    $this->get('/resources?search=etik')
        ->assertSuccessful()
        ->assertSee('name="robots" content="noindex,follow"', false)
        ->assertSee('<link rel="canonical" href="' . route('resources.index') . '">', false);
});

test('story detail pages expose article metadata', function () {
    $this->seed(GiriFoundationSeeder::class);

    $story = Content::query()
        ->published()
        ->stories()
        ->firstOrFail();

    $this->get(route('stories.show', $story))
        ->assertSuccessful()
        ->assertSee('property="og:type" content="article"', false)
        ->assertSee('"@type":"Article"', false)
        ->assertSee('"@type":"BreadcrumbList"', false)
        ->assertSee('aria-label="Breadcrumb"', false)
        ->assertSee($story->displaySeoTitle());
});

test('program detail pages expose breadcrumb metadata and navigation links', function () {
    $this->seed(GiriFoundationSeeder::class);

    $program = Program::query()
        ->published()
        ->firstOrFail();

    $this->get(route('programs.show', $program))
        ->assertSuccessful()
        ->assertSee('"@type":"BreadcrumbList"', false)
        ->assertSee('aria-label="Breadcrumb"', false)
        ->assertSee(route('programs.index'), false)
        ->assertSee(route('donate.show'), false);
});

test('sitemap xml includes canonical public URLs', function () {
    $this->seed(GiriFoundationSeeder::class);

    $program = Program::query()
        ->published()
        ->firstOrFail();

    $story = Content::query()
        ->published()
        ->stories()
        ->firstOrFail();

    $this->get('/sitemap.xml')
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
        ->assertSee('<?xml version="1.0" encoding="UTF-8"?>', false)
        ->assertSee(route('home'), false)
        ->assertSee(route('about'), false)
        ->assertSee(route('programs.show', $program), false)
        ->assertSee(route('stories.show', $story), false)
        ->assertDontSee('/admin', false);
});

test('robots txt blocks the admin panel and references the sitemap', function () {
    $this->get('/robots.txt')
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertSee('User-agent: *')
        ->assertSee('Disallow: /admin')
        ->assertSee('Sitemap: ' . route('sitemap'));
});
