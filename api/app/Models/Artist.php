<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artist extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "artists";
    protected $guarded = [];

    /**
     * Get paginated artists with optional filtering.
     *
     * @param string $filterExpression
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function get($filterExpression = '')
    {
        // Start the query
        $query = self::query();

        // Apply filter if $filterExpression is not empty
        if (!empty($filterExpression)) {
            $query->where('name', 'like', '%' . $filterExpression . '%');
        }

        // Paginate the results with 10 records per page
        return $query->paginate(10);
    }
}
