<?

namespace App\DataTransferObjects\Api\ArtistsDTO;

use Illuminate\Support\Collection;

class ArtistSearchDTO
{

    public static function fromCollection(Collection $artists): array
    {
        return $artists->map(fn($artist) => self::fromModel($artist))->toArray();
    }

    public static function fromModel($artist): array
    {
        return [
            'id' => $artist->id,
            'name' => $artist->name,
            'avatar' => asset($artist->avatar),
        ];
    }
}
