<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OnsenController;
use App\Http\Controllers\SitemapController;

Route::get('/', [OnsenController::class, 'index'])->name('onsen.index');
Route::get('/search', [OnsenController::class, 'search'])->name('onsen.search');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
