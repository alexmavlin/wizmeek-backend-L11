<?

namespace App\DataTransferObjects\Api\UsersDTO;

class UserProfileDataForVisitorsDTO 
{
    public static function fromModel ($user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar ? asset('img/avatars/' . $user->avatar) : ($user->google_avatar ? $user->google_avatar : asset('img/artists/avatars/noAvatar.webp')),
            'description' => $user->description,
            'joined' => date('M Y', strtotime($user->created_at)),
            'following' => $user->following_users_count,
            'followed_by' => $user->followed_by_users_count,
            'is_followed' => $user->is_followed
        ];
    }
}