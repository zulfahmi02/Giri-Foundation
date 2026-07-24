<?php

namespace App\Support;

use App\Models\Activity;
use Illuminate\Support\Uri;

class ActivityShareLinks
{
    /**
     * @return array{whatsapp: string, facebook: string}
     */
    public static function for(Activity $activity): array
    {
        $activityUrl = route('activities.show', $activity);

        return [
            'whatsapp' => (string) Uri::of('https://wa.me/')->withQuery([
                'text' => $activity->title.' — '.$activityUrl,
            ]),
            'facebook' => (string) Uri::of('https://www.facebook.com/sharer/sharer.php')->withQuery([
                'u' => $activityUrl,
            ]),
        ];
    }
}
