<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlaceController;
use App\Models\Place;
use Illuminate\Support\Facades\URL;

// Redirects to the main page of the website.
// Route::redirect('/', '/places');

// Controls 
Route::resource('places', PlaceController::class);
Route::bind('place', function ($value) {
    return Place::where('name', urldecode($value))->firstOrFail();
});


URL::forceScheme('https');

/*
Route::get('/', function () {
    return view('welcome');
});
*/