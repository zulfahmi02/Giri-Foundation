<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Support\ActivityShareLinks;
use Illuminate\Contracts\View\View;

class ActivityController extends Controller
{
    public function show(Activity $activity): View
    {
        abort_unless(
            $activity->status === 'published' && $activity->published_at !== null,
            404,
        );

        $activity->load(['program', 'galleries', 'videos']);

        return view('activities.show', [
            'activity' => $activity,
            'title' => $activity->title,
            'metaDescription' => $activity->summary,
            'shareUrls' => ActivityShareLinks::for($activity),
        ]);
    }
}
