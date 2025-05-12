<?php

use App\Http\Controllers\Admin\Artists\ArtistsCreateController;
use App\Http\Controllers\Admin\Artists\ArtistsDeleteController;
use App\Http\Controllers\Admin\Artists\ArtistsDestroyController;
use App\Http\Controllers\Admin\Artists\ArtistsEditController;
use App\Http\Controllers\Admin\Artists\ArtistsIndexController;
use App\Http\Controllers\Admin\Artists\ArtistsStoreController;
use App\Http\Controllers\Admin\Artists\ArtistsUpdateController;
use App\Http\Controllers\Admin\Artists\ArtistsViewController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\Comments\CommentDeleteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Feedback\FeedbackDeleteController;
use App\Http\Controllers\Admin\Feedback\FeedbackIndexController;
use App\Http\Controllers\Admin\Feedback\FeedbackViewController;
use App\Http\Controllers\Admin\Genres\GenresCreateController;
use App\Http\Controllers\Admin\Genres\GenresDeleteController;
use App\Http\Controllers\Admin\Genres\GenresDestroyController;
use App\Http\Controllers\Admin\Genres\GenresEditController;
use App\Http\Controllers\Admin\Genres\GenresIndexController;
use App\Http\Controllers\Admin\Genres\GenresStoreController;
use App\Http\Controllers\Admin\Genres\GenresUpdateController;
use App\Http\Controllers\Admin\Genres\GenresViewController;
use App\Http\Controllers\Admin\HomePage\HomePageController;
use App\Http\Controllers\Admin\HomePage\SaveHomePageHighlightsController;
use App\Http\Controllers\Admin\HomePage\SearchHighlightedVideosController;
use App\Http\Controllers\Admin\Landing\LandingPageController;
use App\Http\Controllers\Admin\Landing\SaveSelectedVideos;
use App\Http\Controllers\Admin\Landing\SearchVideosController;
use App\Http\Controllers\Admin\Subscribers\EditGlobalEmailController;
use App\Http\Controllers\Admin\Subscribers\SendGlobalEmailController;
use App\Http\Controllers\Admin\Subscribers\SubscribersIndexController;
use App\Http\Controllers\Admin\Users\UsersDeleteController;
use App\Http\Controllers\Admin\Users\UsersViewController;
use App\Http\Controllers\Admin\Videos\GetYouTubeVideoDataController;
use App\Http\Controllers\Admin\Videos\StoreYouTubeVideoController;
use App\Http\Controllers\Admin\Videos\SubmitYouTubeVideoController;
use App\Http\Controllers\Admin\Videos\YouTubeVideoDeleteController;
use App\Http\Controllers\Admin\Videos\YouTubeVideoDeletedIndexController;
use App\Http\Controllers\Admin\Videos\YouTubeVideoDestroyController;
use App\Http\Controllers\Admin\Videos\YouTubeVideoDraftIndexController;
use App\Http\Controllers\Admin\Videos\YouTubeVideoEditController;
use App\Http\Controllers\Admin\Videos\YouTubeVideoRestoreController;
use App\Http\Controllers\Admin\Videos\YouTubeVideoShowController;
use App\Http\Controllers\Admin\Videos\YouTubeVideosIndexController;
use App\Http\Controllers\Admin\Videos\YouTubeVideoUpdateController;
use App\Http\Controllers\Api\Authentication\LoginController;
use App\Http\Controllers\Api\Authentication\LogoutController;
use App\Http\Controllers\Api\Authentication\RegisterController;
use App\Http\Middleware\AuthenticateAdmin;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/email-viewer', function () {
    return view('emails.forgot-password');
});

Route::get('/', [AdminLoginController::class, 'login'])->name('admin_ligin');
Route::post('/admin-authenticate', [AdminLoginController::class, 'authenticate'])->name('admin_authenticate');
Route::get('/admin-logout', [AdminLoginController::class, 'logout'])->name('admin_logout');

Route::post('/api-login', LoginController::class);
Route::post('/api-register', RegisterController::class);
Route::post('/api-logout', LogoutController::class)->middleware('api');

Route::prefix('admin')->middleware([AuthenticateAdmin::class])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('admin_dashboard');

    Route::prefix('/feedbacks')->group(function () {
        Route::get('/', FeedbackIndexController::class)->name('admin_feedback_index');
        Route::get('/{id}', FeedbackViewController::class)->name('admin_feedback_view');
        Route::post('/delete/{id}', FeedbackDeleteController::class)->name('admin_feedback_delete');
    });

    Route::prefix('/homepage')->group(function () {
        Route::get('/', HomePageController::class)->name('admin_homepage_index');
        Route::post('/save', SaveHomePageHighlightsController::class)->name('admin_homepage_save_videos');
        Route::post('/search-editors-pick', [SearchHighlightedVideosController::class, 'editorsPickVideos'])->name('admin_homepage_search_editors_pick');
        Route::post('/search-new', [SearchHighlightedVideosController::class, 'newVideos'])->name('admin_homepage_search_new');
        Route::post('/search-throwback', [SearchHighlightedVideosController::class, 'throwbackVideos'])->name('admin_homepage_search_throwback');
    });

    Route::prefix('/landing')->group(function () {
        Route::get('/', LandingPageController::class)->name('admin_landing_index');
        Route::post('/search', SearchVideosController::class)->name('admin_landing_search');
        Route::post('/save', SaveSelectedVideos::class)->name('admin_landing_save_videos');
    });

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
        Route::get('/', YouTubeVideosIndexController::class)->name('admin_youtube_video_index');
        Route::get('/submit', SubmitYouTubeVideoController::class)->name('admin_submit_youtube_video');
        Route::post('/store', StoreYouTubeVideoController::class)->name('admin_store_youtube_video');
        Route::get('/get-youtube-video-data', GetYouTubeVideoDataController::class)->name('admin_get_youtube_video_data');
        Route::get('/edit/{video}', YouTubeVideoEditController::class)->name('admin_youtube_video_edit');
        Route::put('/update/{video}', YouTubeVideoUpdateController::class)->name('admin_youtube_video_update');
        Route::post('/delete/{video}', YouTubeVideoDeleteController::class)->name('admin_youtube_video_delete');
        Route::get('/deleted-videos', YouTubeVideoDeletedIndexController::class)->name('admin_youtube_videos_deleted');
        Route::get('/draft-videos', YouTubeVideoDraftIndexController::class)->name('admin_youtube_videos_drafts');
        Route::post('/destroy/{id}', YouTubeVideoDestroyController::class)->name('admin_destroy_youtube_video');
        Route::post('/restore/{id}', YouTubeVideoRestoreController::class)->name('admin_restore_youtube_video');
        Route::get('/show/{id}', YouTubeVideoShowController::class)->name('admin_show_youtube_video');
    });

    Route::prefix('comments')->group(function () {
        Route::post('/delete/{comment}', CommentDeleteController::class)->name('admin_delete_comment');
    });

    Route::prefix('subscribers')->group(function () {
        Route::get('/', SubscribersIndexController::class)->name('admin_subscribers_index');
        Route::get('/global-email', EditGlobalEmailController::class)->name('admin_subscribers_editglobalemail');
        Route::post('/send-global-email', SendGlobalEmailController::class)->name('admin_subscribers_sendglobalemail');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', UsersViewController::class)->name('admin_users_index');
        Route::post('/delete/{id}', UsersDeleteController::class)->name('admin_users_destroy');
    });

    Route::prefix('service')->group(function () {
        Route::get('/migrate', function () {
            try {
                Artisan::call('migrate', ['--force' => true]);
                dd('Database migrated successfuly.');
            } catch (\Exception $error) {
                dd('Error while migrating database: ' . $error);
            }            
        });
    });
});
