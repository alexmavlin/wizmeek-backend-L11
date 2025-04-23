<?

namespace App\DataTransferObjects\Api\ArtistsDTO;

use Illuminate\Support\Collection;

class ArtistIndexDTO
{
    public static function fromCollection(Collection $artists): array
    {
        return $artists->map(fn($artist) => self::fromModel($artist))->toArray();
    }

    public static function fromModel($artist): array
    {
        return [
            '_id' => $artist->id,
            'nFan' => 250,
            'shareLink' => "https://wizmeek.com/dashboard/artist/{$artist->id}",
            'cover' => asset($artist->avatar),
            'countries' => self::prepareCountries($artist->countries),
            'name' => $artist->name,
            'bio' => $artist->short_description,
            'type' => self::mergeGenreNames($artist->genres),
            'spotify_link' => $artist->spotify_link,
            'apple_music_link' => $artist->apple_music_link,
            'instagram_link' => $artist->instagram_link,
        ];
    }

    private static function mergeGenreNames($genres): string
    {
        return collect($genres)->pluck('genre')->implode(', ');
    }

    private static function prepareCountries($countries): array
    {
        return $countries->map(function ($country) {
            return [
                'id' => $country->id,
                'name' => $country->name,
                'code' => $country->code,
                'flag' => asset($country->flag),
            ];
        })->toArray();
    }
}
