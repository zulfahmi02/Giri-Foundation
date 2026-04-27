<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Page;
use Illuminate\Contracts\View\View;

class StoryController extends Controller
{
    public function index(): View
    {
        $featuredStory = Content::query()
            ->published()
            ->stories()
            ->with(['category', 'author'])
            ->featured()
            ->latest('published_at')
            ->first();

        if (! $featuredStory) {
            $featuredStory = Content::query()
                ->published()
                ->stories()
                ->with(['category', 'author'])
                ->latest('published_at')
                ->first();
        }

        $secondaryStories = Content::query()
            ->published()
            ->stories()
            ->with(['category', 'author'])
            ->when($featuredStory, fn ($query) => $query->whereKeyNot($featuredStory->getKey()))
            ->latest('published_at')
            ->take(2)
            ->get();

        $excludedStoryIds = collect([$featuredStory?->getKey()])
            ->filter()
            ->merge($secondaryStories->modelKeys())
            ->all();

        $archiveStories = Content::query()
            ->published()
            ->stories()
            ->with('category')
            ->when($excludedStoryIds !== [], fn ($query) => $query->whereNotIn('id', $excludedStoryIds))
            ->latest('published_at')
            ->simplePaginate(6, pageName: 'stories_page')
            ->withQueryString();

        return view('stories.index', [
            'page' => Page::forFrontend('stories'),
            'featuredStory' => $featuredStory,
            'secondaryStories' => $secondaryStories,
            'archiveStories' => $archiveStories,
        ]);
    }

    public function show(Content $content): View
    {
        abort_unless($content->type === 'story' && $content->status === 'published', 404);

        $content->load(['category', 'author', 'tags', 'files']);

        $relatedStories = Content::query()
            ->published()
            ->stories()
            ->with('category')
            ->whereKeyNot($content->id)
            ->when($content->category_id, fn ($query) => $query->where('category_id', $content->category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('stories.show', [
            'story' => $content,
            'relatedStories' => $relatedStories,
            'title' => $content->displayTitle(),
        ]);
    }
}
