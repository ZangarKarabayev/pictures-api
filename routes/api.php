<?php

use App\Http\Controllers\Api\v1\UploadImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('api_token')->group( function() {
    Route::delete('images/{username}/{picture}', [UploadImages::class, 'delete']);
    Route::get('images/{username}/{picture}', [UploadImages::class, 'index']);
    Route::post('images/{username}/{picture}', [UploadImages::class, 'store']);
    // Route::resource('images/{username?}/{picture?}', UploadImages::class);
});








