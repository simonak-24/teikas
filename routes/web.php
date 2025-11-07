<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\PlaceController;
use App\Models\Place;
use Illuminate\Support\Facades\URL;

// Redirects to the main page of the website.
// Route::redirect('/', '/places');

// Collector routes.
Route::get('/collectors', [CollectorController::class, 'index'])->name('collectors.index');
Route::get('/collector/create', [CollectorController::class, 'create'])->name('collectors.create');
Route::post('/collector/create', [CollectorController::class, 'store'])->name('collectors.store');
Route::get('/collector/{id}/', [CollectorController::class, 'show'])->name('collectors.show');
Route::get('/collector/{id}/edit', [CollectorController::class, 'edit'])->name('collectors.edit');
Route::put('/collector/{id}/update', [CollectorController::class, 'update'])->name('collectors.update');
Route::delete('/collector/{id}/delete', [CollectorController::class, 'destroy'])->name('collectors.destroy');

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