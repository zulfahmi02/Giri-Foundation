<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Document;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class PublicationController extends Controller
{
    public function index(): View
    {
        return view('pages.publikasi', [
            'page' => Page::forFrontend('publikasi'),
            'journals' => Content::query()
                ->published()
                ->where('type', 'journal')
                ->with('category')
                ->latest('published_at')
                ->simplePaginate(6, pageName: 'journals_page')
                ->withQueryString(),
            'newsItems' => Content::query()
                ->published()
                ->where('type', 'news')
                ->with('category')
                ->latest('published_at')
                ->simplePaginate(6, pageName: 'news_page')
                ->withQueryString(),
            'articles' => Content::query()
                ->published()
                ->where('type', 'article')
                ->with('category')
                ->latest('published_at')
                ->simplePaginate(6, pageName: 'articles_page')
                ->withQueryString(),
            'opinions' => Content::query()
                ->published()
                ->where('type', 'opinion')
                ->with('category')
                ->latest('published_at')
                ->simplePaginate(6, pageName: 'opinions_page')
                ->withQueryString(),
            'archives' => Document::query()
                ->publiclyAvailable()
                ->latest('published_at')
                ->simplePaginate(6, pageName: 'archives_page')
                ->withQueryString(),
        ]);
    }
}
