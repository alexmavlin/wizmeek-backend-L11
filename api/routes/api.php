<?php

use App\Http\Controllers\Api\Artists\GetArtistsListController;
use App\Http\Controllers\Api\Authentication\ForgotPasswordController;
use App\Http\Controllers\Api\Authentication\ResetPasswordController;
use App\Http\Controllers\Api\Comments\GetSingleVideoCommentsController;
use App\Http\Controllers\Api\Comments\StoreVideoCommentController;
use App\Http\Controllers\Api\Common\VideoArtistsSearchController;
use App\Http\Controllers\Api\Feedback\StoreFeedbackController;
use App\Http\Controllers\Api\Genres\BindGenreTasteController;
use App\Http\Controllers\Api\Genres\GetGenresController;
use App\Http\Controllers\Api\Genres\GetGenresMusictasteController;
use App\Http\Controllers\Api\Genres\UnbindGenreTasteController;
use App\Http\Controllers\Api\Highlights\HomePageHighlightsController;
use App\Http\Controllers\Api\Subscribers\StoreSubscriberController;
use App\Http\Controllers\Api\User\GetUserProfileVideosController;
use App\Http\Controllers\Api\User\UserAvatarUpdateController;
use App\Http\Controllers\Api\User\UserClicksFavoriteOnVideoController;
use App\Http\Controllers\Api\User\UserDeleteAccountController;
use App\Http\Controllers\Api\User\UserFollowsUserController;
use App\Http\Controllers\Api\User\UserGetFollowedUsersController;
use App\Http\Controllers\Api\User\UserGetFollowersController;
use App\Http\Controllers\Api\User\UserLikesVideoCommentController;
use App\Http\Controllers\Api\User\UserLikesVideoController;
use App\Http\Controllers\Api\User\UserNavDetailsController;
use App\Http\Controllers\Api\User\UserProfileController;
use App\Http\Controllers\Api\User\UserProfileEditController;
use App\Http\Controllers\Api\User\UserProfileGuestController;
use App\Http\Controllers\Api\User\UserProfileUpdateController;
use App\Http\Controllers\Api\User\UserProfileVideosController;
use App\Http\Controllers\Api\User\UserUnfollowsUserController;
use App\Http\Controllers\Api\Videos\GetSingleVideoController;
use App\Http\Controllers\Api\Videos\GetUsersFavoriteVideosController;
use App\Http\Controllers\Api\Videos\GetVideosByUserTasteController;
use App\Http\Controllers\Api\Videos\GetVideosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/forgot-password', ForgotPasswordController::class);
Route::post('/reset-password', ResetPasswordController::class);

Route::middleware('auth:sanctum')->prefix('auth')->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/get-header', UserNavDetailsController::class);
        Route::get('/profile', UserProfileController::class);
        Route::get('/profile-edit', UserProfileEditController::class);
        Route::post('/update-description', [UserProfileUpdateController::class, 'updateDescription']);
        Route::post('/update-name-email', [UserProfileUpdateController::class, 'updateNameAndEmail']);
        Route::post('/update-password', [UserProfileUpdateController::class, 'updatePassword']);
        Route::post('/update-avatar', UserAvatarUpdateController::class);
        Route::post('/add-video-to-profile', UserProfileVideosController::class);
        Route::post('/delete-account', UserDeleteAccountController::class);
        Route::post('/follow-user', UserFollowsUserController::class);
        Route::post('/unfollow-user', UserUnfollowsUserController::class);
    }); 

    Route::prefix('feedback')->group(function () {
        Route::post('/store', StoreFeedbackController::class);
    });

    Route::prefix('videos')->group(function () {
        Route::get('/get-favorites', GetUsersFavoriteVideosController::class);
        Route::post('/handle-like-video', UserLikesVideoController::class);
        Route::post('/handle-favorite-video', UserClicksFavoriteOnVideoController::class);
        Route::get('/get-by-taste', GetVideosByUserTasteController::class);
    });

    Route::prefix('comments')->group(function () {
        Route::post('/like', UserLikesVideoCommentController::class);
        Route::post('/store', StoreVideoCommentController::class);
    });

    Route::prefix('genres')->group(function () {
        Route::get('/get-music-taste', GetGenresMusictasteController::class);
        Route::post('/bind-genre', BindGenreTasteController::class);
        Route::post('/unbind-genre', UnbindGenreTasteController::class);
    });
});

Route::post('/media-search', VideoArtistsSearchController::class);

Route::prefix('subscribers')->group(function () {
    Route::post('/store', StoreSubscriberController::class);
});

Route::prefix('videos')->group(function () {
    Route::get('/get', GetVideosController::class);
    Route::get('/get-single/{id}', GetSingleVideoController::class);
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

Route::prefix('user')->group(function () {
    Route::get('/get-as-guest/{uid}', UserProfileGuestController::class);
    Route::get('/get-profile-videos/{uid}', GetUserProfileVideosController::class);
    Route::get('/get-followers/{uid}', UserGetFollowersController::class);
    Route::get('/get-followed/{uid}', UserGetFollowedUsersController::class);
});

Route::prefix('comments')->group(function () {
    Route::get('/get-for-a-single-video/{video_id}', GetSingleVideoCommentsController::class);
});

