<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Genre extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "genres";
    protected $guarded = false;

    /**
     * Get paginated genres with optional filtering.
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
            $query->where('genre', 'like', '%' . $filterExpression . '%');
        }

        // Paginate the results with 10 records per page
        return $query->paginate(10);
    }

    public static function getForSelect() {
        $query = self::query();

        $query->select('id', 'genre');
        return $query->get();
    }
}
