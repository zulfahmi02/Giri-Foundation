<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePartnershipInquiryRequest;
use App\Models\Page;
use App\Models\Partner;
use App\Models\PartnershipInquiry;
use App\Models\Program;
use App\Support\FrontendCache;
use App\Support\PublicSubmissionNotifier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PartnerController extends Controller
{
    public function index(): View
    {
        $partnerPageData = FrontendCache::remember(
            'partners:data',
            fn (): array => [
                'partners' => Partner::query()->where('is_active', true)->orderBy('name')->get(),
                'partnerPrograms' => Program::query()->published()->with(['category', 'partners'])->whereHas('partners')->take(3)->get(),
            ],
            [FrontendCache::PartnersPage],
        );

        return view('pages.partners', [
            'page' => Page::forFrontend('partners'),
            ...$partnerPageData,
        ]);
    }

    public function store(StorePartnershipInquiryRequest $request, PublicSubmissionNotifier $notifier): RedirectResponse
    {
        $partnershipInquiry = PartnershipInquiry::query()->create([
            ...$request->validated(),
            'status' => 'new',
        ]);

        $notifier->sendPartnershipInquiry($partnershipInquiry);

        return to_route('partners.index')->with('status', 'Permintaan kemitraan Anda sudah kami terima.');
    }
}
