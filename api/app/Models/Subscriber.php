<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Subscriber
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber newQuery()
 * @method static \Illuminate\Database\Query\Builder|Subscriber onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Subscriber query()
 * @method static \Illuminate\Database\Query\Builder|Subscriber withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Subscriber withoutTrashed()
 * @method static \Illuminate\Pagination\LengthAwarePaginator getList(string $filterExpression = '')
 *
 * @mixin \Eloquent
 */
class Subscriber extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'subscribers';
    protected $guarded = false;

    /**
     * Retrieves a paginated list of subscribers, optionally filtered by email.
     *
     * @param string $filterExpression Optional filter for the email field.
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    static public function getList($filterExpression = '')
    {
        $query = self::query();

        // Select relevant fields
        $query->select('id', 'email', 'created_at');

        // Apply email filter if provided
        if ($filterExpression) {
            $query->where('email', 'like', '%' . $filterExpression . '%');
        }

        // Return paginated results
        return $query->paginate(25);
    }
}