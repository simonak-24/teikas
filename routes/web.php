<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaceController;
use App\Models\Place;
use Illuminate\Support\Facades\URL;

// Redirects to the main page of the website.
// Route::redirect('/', '/places');

// Place routes.
Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
Route::get('/places/create', [PlaceController::class, 'create'])->name('places.create');
Route::post('/places/create', [PlaceController::class, 'store'])->name('places.store');
Route::get('/places/{id}/{name}', [PlaceController::class, 'edit'])->name('places.edit');
Route::put('/places/{id}', [PlaceController::class, 'update'])->name('places.update');
Route::delete('/places/delete/{id}', [PlaceController::class, 'destroy'])->name('places.destroy');

URL::forceScheme('https');

/*
Route::get('/', function () {
    return view('welcome');
});
*/