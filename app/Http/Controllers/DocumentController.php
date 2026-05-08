<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Page;
use App\Support\FrontendCache;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $category = $request->string('category')->toString();

        $categories = FrontendCache::remember(
            'resources:categories',
            fn () => Document::query()->select('category')->distinct()->whereNotNull('category')->pluck('category'),
            [FrontendCache::ResourcesPage],
        );

        $documents = Document::query()
            ->publiclyAvailable()
            ->when($category !== '', fn ($query) => $query->where('category', $category))
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($nestedQuery) use ($search): void {
                    $nestedQuery
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest('published_at')
            ->simplePaginate(10, pageName: 'documents_page')
            ->withQueryString();

        return view('pages.resources', [
            'page' => Page::forFrontend('resources'),
            'documents' => $documents,
            'categories' => $categories,
            'activeCategory' => $category,
            'search' => $search,
        ]);
    }

    public function download(Document $document): BinaryFileResponse|RedirectResponse
    {
        abort_unless($document->isPublishedPublicly(), Response::HTTP_NOT_FOUND);

        $document->increment('download_count');

        if ($document->isExternalFile()) {
            return redirect()->away($document->file_url);
        }

        $downloadablePath = $document->downloadablePath();

        abort_unless($downloadablePath !== null, Response::HTTP_NOT_FOUND);

        return response()->download($downloadablePath, $document->downloadFilename());
    }
}
