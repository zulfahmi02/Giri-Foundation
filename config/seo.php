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

    'canonical_query_parameters_by_route' => [
        'programs.index' => [
            'active_page',
            'partnership_page',
            'upcoming_page',
            'archived_page',
        ],
        'media.index' => [
            'activities_page',
            'videos_page',
        ],
        'publications.index' => [
            'stories_page',
            'journals_page',
            'news_page',
            'articles_page',
            'opinions_page',
            'archives_page',
        ],
        'stories.index' => [
            'stories_page',
        ],
        'resources.index' => [
            'category',
            'documents_page',
        ],
    ],

    'canonical_pagination_parameters' => [
        'active_page',
        'partnership_page',
        'upcoming_page',
        'archived_page',
        'activities_page',
        'videos_page',
        'stories_page',
        'journals_page',
        'news_page',
        'articles_page',
        'opinions_page',
        'archives_page',
        'documents_page',
    ],
];
