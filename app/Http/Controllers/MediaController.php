<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Page;
use App\Models\Video;
use Illuminate\Contracts\View\View;

class MediaController extends Controller
{
    public function index(): View
    {
        return view('pages.media', [
            'page' => Page::forFrontend('media'),
            'activities' => Activity::query()
                ->published()
                ->with('program')
                ->latest('published_at')
                ->simplePaginate(6, pageName: 'activities_page')
                ->withQueryString(),
            'videos' => Video::query()
                ->published()
                ->orderBy('sort_order')
                ->latest('published_at')
                ->simplePaginate(4, pageName: 'videos_page')
                ->withQueryString(),
        ]);
    }
}
