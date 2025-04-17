<?

namespace App\DataTransferObjects\Api\CommentsDTO;

class CommentDTO
{
    public static function fromCollection ($comments): array
    {
        return array_values(
            $comments->map(fn($comment) => self::fromModel($comment))->toArray()
        );
        //return $artists->map(fn ($artist) => self::fromModel($artist))->toArray();
    }

    public static function fromModel ($comment): array
    {
        return [
            "_id" => $comment->id,
            "content" => $comment->content,
            "created" => strtotime($comment->created_at),
            "isLiked" => $comment->relationLoaded('userLikes') && $comment->userLikes->isNotEmpty(),
            "nLike" => $comment->user_likes_count,
            "user" => [
                "id" => $comment->user->id,
                "name" => $comment->user->name,
                "avatar" => $comment->user->avatar ? asset('img/avatars/' . $comment->user->avatar) : ($comment->user->google_avatar ? $comment->user->google_avatar : asset('img/artists/avatars/noAvatar.webp'))
            ]
        ];
    }
}