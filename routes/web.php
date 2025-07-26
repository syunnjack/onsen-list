<?php

use Illuminate\Support\Facades\Route;
//use Livewire\Volt\Volt;
use App\Http\Controllers\OnsenController;

Route::get('/', function () {
    return view('welcome');

});

//require __DIR__.'/auth.php';

Route::get('/', [OnsenController::class, 'index']);
Route::get('/search',[OnsenController::class,'search']);
Route::get('/search', [SpotController::class, 'search'])->name('spots.search');
Route::get('/search', [OnsenController::class, 'search'])->name('onsen.search');
