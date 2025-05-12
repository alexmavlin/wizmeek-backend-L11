<?php

namespace App\Models;

use App\QueryFilters\Admin\FeedBack\FeedbackLoadUserfilter;
use App\QueryFilters\Admin\FeedBack\FeedbackOrderFilter;
use App\QueryFilters\Admin\FeedBack\GetForAdminSelectFilter;
use App\QueryFilters\CommonFindFilter;
use App\QueryFilters\CommonPaginatorFilter;
use App\QueryFilters\CommonSearchFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pipeline\Pipeline;

class Feedback extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'feedbacks';
    protected $guarded = ['id'];

    public static function getForAdmin()
    {
        $searchString = request('search_string');

        $feedbacks = app(Pipeline::class)
            ->send(self::query())
            ->through([
                new CommonSearchFilter($searchString, 'subject', [
                    [
                        'name' => 'user',
                        'column' => 'name'
                    ],
                    [
                        'name' => 'user',
                        'column' => 'emial'
                    ]
                ]),
                GetForAdminSelectFilter::class,
                FeedbackLoadUserfilter::class,
                FeedbackOrderFilter::class,
                new CommonPaginatorFilter(10)
            ])
            ->thenReturn();

        return $feedbacks;
    }

    public static function getSingleforAdmin($id)
    {
        $feedback = app(Pipeline::class)
            ->send(self::query())
            ->through([
                GetForAdminSelectFilter::class,
                FeedbackLoadUserfilter::class,
                new CommonFindFilter($id)
            ])
            ->thenReturn();

        return $feedback;
    }

    public static function deleteFeedback($id)
    {
        $query = self::query();

        $feedback = $query->findOrFail($id);

        return $feedback->delete();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }
}
