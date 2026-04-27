<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PublicationController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StoryController;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/', HomeController::class)->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('programs.show');
Route::get('/media', [MediaController::class, 'index'])->name('media.index');
Route::get('/publikasi', [PublicationController::class, 'index'])->name('publications.index');
Route::get('/stories', [StoryController::class, 'index'])->name('stories.index');
Route::get('/stories/{content}', [StoryController::class, 'show'])->name('stories.show');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::get('/donate', [DonationController::class, 'show'])->name('donate.show');
Route::get('/resources', [DocumentController::class, 'index'])->name('resources.index');
Route::get('/partners', [PartnerController::class, 'index'])->name('partners.index');

Route::middleware('throttle:public-form-submissions')->group(function (): void {
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
    Route::post('/donate', [DonationController::class, 'store'])->name('donate.store');
    Route::post('/partners/inquiries', [PartnerController::class, 'store'])->name('partners.store');
});
