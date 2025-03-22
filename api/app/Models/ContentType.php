<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Retrieves a list of all records for selection purposes.
     *
     * This method fetches all entries with their ID and name, 
     * which can be used in dropdowns or selection lists.
     *
     * @return \Illuminate\Database\Eloquent\Collection The collection of records containing ID and name.
     */
    public static function getForSelect()
    {
        $query = self::query();
        $query->select('id', 'name');
        return $query->get();
    }
}
