<?

namespace App\DataTransferObjects\Api\GenresDTO;

class GenreTasteDTO
{
    public static function fromCollection($genres): array
    {
        return $genres->map(function ($genre) {
            return [
                'id' => $genre->id,
                'genre' => $genre->genre,
                'color' => $genre->color,
                'image' => asset($genre->img_link),
                'isGenreTasty' => (bool) $genre->isGenreTasty,
            ];
        })->toArray();
    }
}