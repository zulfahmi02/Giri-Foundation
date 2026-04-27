<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDonationRequest;
use App\Models\Document;
use App\Models\Donation;
use App\Models\DonationCampaign;
use App\Models\Donor;
use App\Models\Page;
use App\Support\FrontendCache;
use App\Support\PublicSubmissionNotifier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function show(): View
    {
        $donatePageData = FrontendCache::remember(
            'donate:data',
            fn (): array => [
                'campaign' => DonationCampaign::query()->published()->featured()->with('updates')->firstOrFail(),
                'documents' => Document::query()->publiclyAvailable()->latest('published_at')->take(3)->get(),
            ],
            [FrontendCache::DonatePage],
        );

        return view('pages.donate', [
            'page' => Page::forFrontend('donate'),
            ...$donatePageData,
        ]);
    }

    public function store(StoreDonationRequest $request, PublicSubmissionNotifier $notifier): RedirectResponse
    {
        $campaign = DonationCampaign::query()->published()->featured()->firstOrFail();
        $validated = $request->validated();

        $donation = DB::transaction(function () use ($campaign, $validated): Donation {
            $donor = Donor::query()->updateOrCreate(
                ['email' => $validated['email']],
                [
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'] ?? null,
                    'is_anonymous' => (bool) ($validated['is_anonymous'] ?? false),
                ],
            );

            return Donation::query()->create([
                'campaign_id' => $campaign->id,
                'donor_id' => $donor->id,
                'invoice_number' => 'DON-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_channel' => $validated['payment_channel'] ?? null,
                'payment_status' => 'pending',
                'message' => $validated['message'] ?? null,
            ]);
        });

        $notifier->sendDonationIntent($donation);

        return to_route('donate.show')->with('status', 'Minat donasi Anda sudah dicatat. Tim kami akan menghubungi Anda untuk detail pembayaran.');
    }
}
