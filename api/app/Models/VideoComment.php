<?php

namespace App\Models;

use App\DataTransferObjects\Api\CommentsDTO\CommentDTO;
use App\QueryFilters\Api\Comments\CommentAddIsLikedFlagFilter;
use App\QueryFilters\Api\Comments\CommentExcludeWhereUserDeletedFilter;
use App\QueryFilters\Api\Comments\CommentGetByIdFilter;
use App\QueryFilters\Api\Comments\CommentGetByVideoIdFilter;
use App\QueryFilters\Api\Comments\CommentGetUserFilter;
use App\QueryFilters\Api\Comments\CommentOrderByCreationDateFilter;
use App\QueryFilters\Api\Comments\CommentSelectFilter;
use App\QueryFilters\Api\Comments\CommentWithLikesCountFilter;
use App\Traits\MediaCardTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Auth;

class VideoComment extends Model
{
    use HasFactory, MediaCardTrait;

    protected $table = "video_comments";
    protected $guarded = ['id'];

    /**
     * Retrieve a single comment with user and like details.
     *
     * This method fetches a specific comment by its ID using a pipeline of filters.
     * It includes associated user information, like counts, and whether the
     * currently authenticated user has liked the comment.
     *
     * @param int $id The ID of the comment to retrieve.
     * @return array The formatted comment data as an array.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the comment is not found.
     */
    public static function getComment($id): array
    {

        $userId = Auth::check() ? Auth::user()->id : null;

        $comment = app(Pipeline::class)
            ->send(self::query())
            ->through([
                CommentSelectFilter::class,
                CommentExcludeWhereUserDeletedFilter::class,
                CommentGetUserFilter::class,
                new CommentAddIsLikedFlagFilter($userId),
                CommentWithLikesCountFilter::class,
                new CommentGetByIdFilter($id)
            ])
            ->thenReturn();

        return CommentDTO::fromModel($comment);
    }

    /**
     * Retrieve the latest comments for a specific video.
     *
     * This method fetches up to 10 comments associated with the given video ID.
     * It applies several filters via a pipeline, including user deletion checks,
     * user data enrichment, like flags for the authenticated user (if any),
     * and like count aggregation. The results are sorted by creation date.
     *
     * @param int $video_id The ID of the video for which to retrieve comments.
     * @return array An array of comment data formatted for API response.
     */
    public static function getForSingleVideo($video_id): array
    {
        $userId = Auth::check() ? Auth::user()->id : null;

        $comments = app(Pipeline::class)
            ->send(self::query())
            ->through([
                CommentSelectFilter::class,
                CommentExcludeWhereUserDeletedFilter::class,
                new CommentGetByVideoIdFilter($video_id),
                CommentGetUserFilter::class,
                new CommentAddIsLikedFlagFilter($userId),
                CommentOrderByCreationDateFilter::class,
                CommentWithLikesCountFilter::class
            ])
            ->thenReturn()
            ->limit(10)
            ->get()
            ->sortBy('created_at', SORT_REGULAR);

        return CommentDTO::fromCollection($comments);
    }

    public function getContent()
    {
        return $this->content;
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }

    public function youtubeVideo()
    {
        return $this->belongsTo(
            YouTubeVideo::class,
            'youtube_video_id',
            'id'
        );
    }

    public function userLikes()
    {
        return $this->belongsToMany(
            User::class,
            'users_video_comments',
            'video_comment_id',
            'user_id'
        );
    }
}
