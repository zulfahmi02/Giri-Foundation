<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    public function __invoke(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Sitemap: ' . route('sitemap'),
            '',
        ]);

        return response($content)
            ->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
