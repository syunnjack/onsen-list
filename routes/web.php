<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OnsenController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;

Route::get('/', [OnsenController::class, 'index'])->name('onsen.index');
Route::get('/search', [OnsenController::class, 'search'])->name('onsen.search');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::post('/reviews', [ReviewController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('reviews.store');
