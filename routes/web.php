<?php

use App\Http\Controllers\ImageLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::post('/extract-image-links', [ImageLinkController::class, 'extractImageLinks']);

