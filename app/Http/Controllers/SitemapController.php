<?php

namespace App\Http\Controllers;

use App\Support\SitemapBuilder;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(SitemapBuilder $sitemapBuilder): Response
    {
        return response($sitemapBuilder->toXml())
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
