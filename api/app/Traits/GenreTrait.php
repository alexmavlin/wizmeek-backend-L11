<?php

namespace App\Traits;

trait GenreTrait
{
    /**
     * Merge genre names into a comma-separated string.
     *
     * This method takes a collection or an array of genres and extracts the genre names,
     * returning them as a single string separated by commas.
     *
     * @param \Illuminate\Support\Collection|array $genres The collection or array of genres.
     * @return string A comma-separated string of genre names.
     */
    public static function mergeGenreNames($genres)
    {
        $genresArray = $genres instanceof \Illuminate\Support\Collection ? $genres->toArray() : $genres;

        return implode(', ', array_map(fn($genre) => $genre['genre'], $genresArray));
    }
}
