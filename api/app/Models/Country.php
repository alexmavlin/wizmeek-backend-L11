<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Get countries for select inputs.
     *
     * @param string $filterExpression
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function getForSelect()
    {
        $query = self::query();

        $query->select('id', 'name');
        $query->orderBy('name', 'ASC');

        // Paginate the results with 10 records per page
        return $query->get();
    }
}
