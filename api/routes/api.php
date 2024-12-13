<?php

use App\Http\Controllers\Api\Artists\GetArtistsListController;
use App\Http\Controllers\Api\Authentication\LoginController;
use App\Http\Controllers\Api\Genres\GetGenresController;
use App\Http\Controllers\Api\Highlights\HomePageHighlightsController;
use App\Http\Controllers\Api\Subscribers\StoreSubscriberController;
use App\Http\Controllers\Api\User\UserNavDetailsController;
use App\Http\Controllers\Api\User\UserProfileController;
use App\Http\Controllers\Api\Videos\GetUsersFavoriteVideosController;
use App\Http\Controllers\Api\Videos\GetVideosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/get-header', UserNavDetailsController::class);
        Route::get('/profile', UserProfileController::class);
    }); 

    Route::prefix('videos')->group(function () {
        Route::get('/get-favorites', GetUsersFavoriteVideosController::class);
    });
});

Route::prefix('subscribers')->group(function () {
    Route::post('/store', StoreSubscriberController::class);
});

Route::prefix('videos')->group(function () {
    Route::get('/get', GetVideosController::class);
});

Route::prefix('highlights')->group(function () {
    Route::get('/get', HomePageHighlightsController::class);
});

Route::prefix('artists')->group(function() {
    Route::get('/get', GetArtistsListController::class);
});

Route::prefix('genres')->group(function () {
    Route::get('/get', GetGenresController::class);
});
