<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Retrieves a list of all countries for selection purposes.
     *
     * This method fetches all records containing their ID and genre name, 
     * which can be used in dropdowns or selection lists.
     *
     * @return \Illuminate\Database\Eloquent\Collection The collection of genres containing ID and genre name.
     */
    public static function getForSelect()
    {
        $query = self::query();

        $query->select('id', 'name');
        $query->orderBy('name', 'ASC');

        // Paginate the results with 10 records per page
        return $query->get();
    }

    public function artists()
    {
        return $this->belongsToMany(
            Artist::class,
            'artists_countries',
            'country_id',
            'artist_id'
        );
    }
}
