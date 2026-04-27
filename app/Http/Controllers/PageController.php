<?php

namespace App\Http\Controllers;

use App\Models\OrganizationProfile;
use App\Models\Page;
use App\Models\TeamMember;
use App\Support\FrontendCache;
use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $aboutPageData = FrontendCache::remember(
            'about:data',
            function (): array {
                $teamMembers = TeamMember::query()
                    ->where('is_active', true)
                    ->structural()
                    ->with('divisionRecord')
                    ->orderBy('sort_order')
                    ->get();

                return [
                    'profile' => OrganizationProfile::query()->firstOrFail(),
                    'teamMembers' => TeamMember::buildHierarchy($teamMembers),
                ];
            },
            [FrontendCache::AboutPage],
        );

        return view('pages.about', [
            'page' => Page::forFrontend('about'),
            ...$aboutPageData,
        ]);
    }
}
