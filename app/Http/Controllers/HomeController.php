<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Content;
use App\Models\Document;
use App\Models\OrganizationProfile;
use App\Models\Page;
use App\Models\Program;
use App\Models\Video;
use App\Support\FrontendCache;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $homePageData = FrontendCache::remember(
            'home:data',
            function (): array {
                $profile = OrganizationProfile::query()->firstOrFail();

                return [
                    'profile' => $profile,
                    'heroSummary' => Str::contains($profile->short_description, 'Building resilient communities')
                        ? 'Membangun komunitas yang tangguh melalui tindakan autentik, cerita editorial, dan kemitraan jangka panjang.'
                        : $profile->short_description,
                    'featuredProgram' => Program::query()
                        ->published()
                        ->inPhase('active')
                        ->with(['category', 'partners'])
                        ->latest('is_featured')
                        ->latest('published_at')
                        ->first(),
                    'latestActivities' => Activity::query()
                        ->published()
                        ->with('program')
                        ->latest('published_at')
                        ->take(3)
                        ->get(),
                    'latestVideos' => Video::query()
                        ->published()
                        ->orderBy('sort_order')
                        ->latest('published_at')
                        ->take(2)
                        ->get(),
                    'latestEditorialPublications' => Content::query()
                        ->published()
                        ->editorialPublications()
                        ->with('category')
                        ->latest('published_at')
                        ->take(3)
                        ->get(),
                    'latestArchiveDocuments' => Document::query()
                        ->publiclyAvailable()
                        ->latest('published_at')
                        ->take(2)
                        ->get(),
                ];
            },
            [FrontendCache::HomePage],
        );

        return view('pages.home', [
            'page' => Page::forFrontend('home'),
            ...$homePageData,
        ]);
    }
}
