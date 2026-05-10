<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConsultationRequest;
use App\Models\Consultation;
use App\Models\Page;
use App\Support\PublicSubmissionNotifier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ConsultationController extends Controller
{
    public function show(): View
    {
        return view('pages.consultation', [
            'page' => Page::forFrontend('consultation'),
        ]);
    }

    public function store(StoreConsultationRequest $request, PublicSubmissionNotifier $notifier): RedirectResponse
    {
        $consultation = Consultation::query()->create([
            ...$request->validated(),
            'status' => 'new',
        ]);

        $notifier->sendConsultation($consultation);

        return to_route('consultation.show')->with('status', 'Permintaan konsultasi Anda sudah kami terima.');
    }
}
