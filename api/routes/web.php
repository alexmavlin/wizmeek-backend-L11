<?php

use App\Http\Controllers\Admin\Artists\ArtistsCreateController;
use App\Http\Controllers\Admin\Artists\ArtistsDeleteController;
use App\Http\Controllers\Admin\Artists\ArtistsDestroyController;
use App\Http\Controllers\Admin\Artists\ArtistsEditController;
use App\Http\Controllers\Admin\Artists\ArtistsIndexController;
use App\Http\Controllers\Admin\Artists\ArtistsStoreController;
use App\Http\Controllers\Admin\Artists\ArtistsUpdateController;
use App\Http\Controllers\Admin\Artists\ArtistsViewController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Genres\GenresCreateController;
use App\Http\Controllers\Admin\Genres\GenresDeleteController;
use App\Http\Controllers\Admin\Genres\GenresDestroyController;
use App\Http\Controllers\Admin\Genres\GenresEditController;
use App\Http\Controllers\Admin\Genres\GenresIndexController;
use App\Http\Controllers\Admin\Genres\GenresStoreController;
use App\Http\Controllers\Admin\Genres\GenresUpdateController;
use App\Http\Controllers\Admin\Genres\GenresViewController;
use App\Http\Controllers\Admin\Videos\GetYouTubeVideoDataController;
use App\Http\Controllers\Admin\Videos\SubmitYouTubeVideoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('admin_dashboard');

    Route::prefix('artists')->group(function () {
        Route::get('/', ArtistsIndexController::class)->name('admin_artists_index');
        Route::get('/create', ArtistsCreateController::class)->name('admin_artists_create');
        Route::post('/store', ArtistsStoreController::class)->name('admin_artists_store');
        Route::get('/view/{artist}', ArtistsViewController::class)->name('admin_artists_view');
        Route::get('/edit/{artist}', ArtistsEditController::class)->name('admin_artists_edit');
        Route::post('/update/{artist}', ArtistsUpdateController::class)->name('admin_artists_update');
        Route::post('/delete/{artist}', ArtistsDeleteController::class)->name('admin_artists_delete');
        Route::post('/destroy/{artist}', ArtistsDestroyController::class)->name('admin_artists_destroy');
    });

    Route::prefix('genres')->group(function () {
        Route::get('/', GenresIndexController::class)->name('admin_genres_index');
        Route::get('/create', GenresCreateController::class)->name('admin_genres_create');
        Route::post('/store', GenresStoreController::class)->name('admin_genres_store');
        Route::get('/view/{genre}', GenresViewController::class)->name('admin_genres_view');
        Route::get('/edit/{genre}', GenresEditController::class)->name('admin_genres_edit');
        Route::put('/update/{genre}', GenresUpdateController::class)->name('admin_genres_update');
        Route::post('/delete/{genre}', GenresDeleteController::class)->name('admin_genres_delete');
        Route::post('/destroy/{genre}', GenresDestroyController::class)->name('admin_genres_destroy');
    });

    Route::prefix('youtube-videos')->group(function () {
        Route::get('/submit', SubmitYouTubeVideoController::class)->name('admin_submit_youtube_video');
        Route::get('/get-youtube-video-data', GetYouTubeVideoDataController::class)->name('admin_get_youtube_video_data');
    });
});
