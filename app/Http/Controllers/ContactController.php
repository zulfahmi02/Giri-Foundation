<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Support\PublicSubmissionNotifier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function show(): View
    {
        return view('pages.contact', [
            'page' => Page::forFrontend('contact'),
        ]);
    }

    public function store(StoreContactMessageRequest $request, PublicSubmissionNotifier $notifier): RedirectResponse
    {
        $contactMessage = ContactMessage::query()->create([
            ...$request->validated(),
            'status' => 'new',
        ]);

        $notifier->sendContactMessage($contactMessage);

        return to_route('contact.show')->with('status', 'Pesan Anda sudah terkirim.');
    }
}
