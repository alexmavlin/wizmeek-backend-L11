<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentType extends Model
{
    use HasFactory, SoftDeletes;

    public static function getForSelect() {
        $query = self::query();
        $query->select('id', 'name');
        return $query->get();
    }
}
