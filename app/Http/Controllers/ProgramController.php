<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\Page;
use App\Models\Program;
use Illuminate\Contracts\View\View;

class ProgramController extends Controller
{
    public function index(): View
    {
        return view('programs.index', [
            'page' => Page::forFrontend('programs'),
            'activePrograms' => Program::query()
                ->published()
                ->inPhase('active')
                ->with(['category', 'partners'])
                ->doesntHave('partners')
                ->latest('published_at')
                ->simplePaginate(6, pageName: 'active_page')
                ->withQueryString(),
            'partnershipPrograms' => Program::query()
                ->published()
                ->inPhase('active')
                ->with(['category', 'partners'])
                ->has('partners')
                ->latest('published_at')
                ->simplePaginate(6, pageName: 'partnership_page')
                ->withQueryString(),
            'upcomingPrograms' => Program::query()
                ->published()
                ->inPhase('upcoming')
                ->with(['category', 'partners'])
                ->orderBy('start_date')
                ->simplePaginate(6, pageName: 'upcoming_page')
                ->withQueryString(),
            'archivedPrograms' => Program::query()
                ->published()
                ->inPhase('archived')
                ->with(['category', 'partners'])
                ->latest('end_date')
                ->latest('published_at')
                ->simplePaginate(6, pageName: 'archived_page')
                ->withQueryString(),
        ]);
    }

    public function show(Program $program): View
    {
        abort_unless($program->status === 'published', 404);

        $program->load([
            'category',
            'galleries',
            'partners',
            'activities' => fn ($query) => $query->published(),
        ]);

        $relatedStories = Content::query()->published()->stories()->latest('published_at')->take(3)->get();
        $relatedPrograms = Program::query()
            ->published()
            ->with('category')
            ->whereKeyNot($program->id)
            ->when($program->category, fn ($query) => $query->where('category_id', $program->category_id))
            ->take(3)
            ->get();

        return view('programs.show', [
            'program' => $program,
            'relatedStories' => $relatedStories,
            'relatedPrograms' => $relatedPrograms,
            'title' => $program->title,
        ]);
    }
}
