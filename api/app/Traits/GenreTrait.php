<?php

namespace App\Traits;

trait GenreTrait {
    public static function mergeGenreNames($genres) {
        // Convert to array if $genres is a collection
        $genresArray = $genres instanceof \Illuminate\Support\Collection ? $genres->toArray() : $genres;

        return implode(', ', array_map(fn($genre) => $genre['genre'], $genresArray));
    }
}