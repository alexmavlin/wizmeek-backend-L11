<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'feedbacks';
    protected $guarded = ['id'];

    public static function getForAdmin()
    {
        $searchString = request('search_string');

        $query = self::query();

        if ($searchString) {
            $query->where('subject', 'like', '%' . $searchString . '%');
            $query->orWhereHas('user', function($q) use ($searchString) {
                $q->where('name', 'like', '%' . $searchString . '%');
            });
            $query->orWhereHas('user', function ($q) use ($searchString) {
                $q->where('email', 'like', '%' . $searchString . '%');
            });
        }

        $query->select(
            'id',
            'user_id',
            'subject',
            'unread',
            'created_at'
        );

        $query->with([
            'user' => function ($q) {
                $q->select(
                    'id',
                    'name',
                    'avatar',
                    'google_avatar'
                );
            }
        ]);

        $query->orderBy('created_at', 'DESC');

        $feedbacks = $query->paginate(10);

        return $feedbacks;
    }

    public static function getSingleforAdmin($id)
    {
        $query = self::query();

        $query->select(
            'id',
            'user_id',
            'subject',
            'message',
            'files',
            'created_at'
        );

        $query->with([
            'user' => function ($q) {
                $q->select(
                    'id',
                    'name',
                    'email',
                    'avatar',
                    'google_avatar'
                );
            }
        ]);

        return $query->findOrFail($id);
    }

    public static function deleteFeedback($id)
    {
        $query = self::query();

        $feedback = $query->findOrFail($id);

        return $feedback->delete();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
