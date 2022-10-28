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
Route::get('images/{username}/{subfolder?}/{picture?}', [UploadImages::class, 'index']);

Route::middleware('api_token')->group( function() {
    // Route::resource('images/{username?}/{picture?}', UploadImages::class);
    
    Route::delete('images/{username}/{subfolder?}/{picture?}', [UploadImages::class, 'delete']);
    Route::post('images/{username}/{subfolder?}', [UploadImages::class, 'store']);
});








