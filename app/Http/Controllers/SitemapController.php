<?php

namespace App\Http\Controllers;

use App\Support\SitemapBuilder;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(SitemapBuilder $sitemapBuilder): Response
    {
        return response()
            ->view('seo.sitemap', [
                'urls' => $sitemapBuilder->build(),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
