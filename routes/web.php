<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\NarratorController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\LegendController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\URL;

// Redirects to the main page of the website (at the moment, meant for testing).
Route::get('/', function () {
    return view('home');
})->name('home');

// Collector routes.
Route::get('/collectors', [CollectorController::class, 'index'])->name('collectors.index');
Route::get('/collector/create', [CollectorController::class, 'create'])->name('collectors.create')->middleware('auth');
Route::post('/collector/create', [CollectorController::class, 'store'])->name('collectors.store');
Route::get('/collector/{id}/', [CollectorController::class, 'show'])->name('collectors.show');
Route::get('/collector/{id}/edit', [CollectorController::class, 'edit'])->name('collectors.edit');
Route::put('/collector/{id}/update', [CollectorController::class, 'update'])->name('collectors.update');
Route::delete('/collector/{id}/delete', [CollectorController::class, 'destroy'])->name('collectors.destroy');

// Narrator routes.
Route::get('/narrators', [NarratorController::class, 'index'])->name('narrators.index');
Route::get('/narrator/create', [NarratorController::class, 'create'])->name('narrators.create');
Route::post('/narrator/create', [NarratorController::class, 'store'])->name('narrators.store');
Route::get('/narrator/{id}/', [NarratorController::class, 'show'])->name('narrators.show');
Route::get('/narrator/{id}/edit', [NarratorController::class, 'edit'])->name('narrators.edit');
Route::put('/narrator/{id}/update', [NarratorController::class, 'update'])->name('narrators.update');
Route::delete('/narrator/{id}/delete', [NarratorController::class, 'destroy'])->name('narrators.destroy');

// Source routes.
Route::get('/sources', [SourceController::class, 'index'])->name('sources.index');
Route::get('/source/create', [SourceController::class, 'create'])->name('sources.create');
Route::post('/source/create', [SourceController::class, 'store'])->name('sources.store');
Route::get('/source/{id}/', [SourceController::class, 'show'])->name('sources.show');
Route::get('/source/{id}/edit', [SourceController::class, 'edit'])->name('sources.edit');
Route::put('/source/{id}/update', [SourceController::class, 'update'])->name('sources.update');
Route::delete('/source/{id}/delete', [SourceController::class, 'destroy'])->name('sources.destroy');

// Place routes.
Route::get('/places', [PlaceController::class, 'index'])->name('places.index');
Route::get('/place/create', [PlaceController::class, 'create'])->name('places.create');
Route::post('/place/create', [PlaceController::class, 'store'])->name('places.store');
Route::get('/place/{id}/', [PlaceController::class, 'show'])->name('places.show');
Route::get('/place/{id}/edit', [PlaceController::class, 'edit'])->name('places.edit');
Route::put('/place/{id}/update', [PlaceController::class, 'update'])->name('places.update');
Route::delete('/place/{id}/delete', [PlaceController::class, 'destroy'])->name('places.destroy');

// Legend routes.
Route::get('/legends', [LegendController::class, 'index'])->name('legends.index');
Route::get('/legend/create', [LegendController::class, 'create'])->name('legends.create');
Route::post('/legend/create', [LegendController::class, 'store'])->name('legends.store');
Route::get('/legend/{identifier}/', [LegendController::class, 'show'])->name('legends.show');
Route::get('/legend/{identifier}/edit', [LegendController::class, 'edit'])->name('legends.edit');
Route::put('/legend/{identifier}/update', [LegendController::class, 'update'])->name('legends.update');
Route::delete('/legend/{identifier}/delete', [LegendController::class, 'destroy'])->name('legends.destroy');

// User routes.
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/authenticate', [UserController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

URL::forceScheme('https');

/*
Route::get('/', function () {
    return view('welcome');
});
*/