<?

namespace App\DataTransferObjects\Api\GenresDTO;

class GenreIndexDTO
{

    public static function fromCollection($genres): array
    {
        return $genres->map(function ($genre) {
            return [
                'id' => $genre->id,
                'label' => $genre->genre,
                'color' => $genre->color,
            ];
        })->toArray();
    }
}
