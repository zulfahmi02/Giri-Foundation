<?php

return [
    'site_name' => env('SEO_SITE_NAME', 'GIRI Foundation'),

    'site_alternate_name' => env('SEO_SITE_ALTERNATE_NAME', 'Yayasan Giri Nusantara Sejahtera'),

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
