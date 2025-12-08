<?php

namespace App\QueryFilters\Api\YouTubeVideos;

use Closure;

class YouTubeVideoGenreFilter
{
    public function handle ($query, Closure $next)
    {
        $genre = request()->header('X-Genre');

        \Log::info($genre);

        if ($genre && $genre !== '' && $genre !== 'All') {
            // If genre is numeric, treat it as an ID
            if (is_numeric($genre)) {
                $query->whereHas('genre', function ($q) use ($genre) {
                    $q->where('id', $genre);
                });
            } else {
                // If genre is a string, treat it as a label
                $query->whereHas('genre', function ($q) use ($genre) {
                    $q->where('genre', 'like', '%' . $genre . '%');
                });
            }
        }

        return $next($query);
    }
}