<?php

return [
    'google_site_verification' => env('GOOGLE_SITE_VERIFICATION'),

    'canonical_ignored_query_parameters' => [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'utm_id',
        'gclid',
        'fbclid',
    ],
];
