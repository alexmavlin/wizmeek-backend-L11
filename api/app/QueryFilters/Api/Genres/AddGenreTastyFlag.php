<?

namespace App\QueryFilters\Api\Genres;

use Closure;
use Illuminate\Support\Facades\Auth;

class AddGenreTastyFlag
{

    public function handle($query, Closure $next)
    {
        $results = $query->get();

        $user = Auth::user();
        $user->loadMissing(['genreTaste:id']);

        $tastyGenreIds = $user->genreTaste->pluck('id')->toArray();

        return $next(
            $results->map(function ($genre) use ($tastyGenreIds) {
                $genre->isGenreTasty = in_array($genre->id, $tastyGenreIds);
                return $genre;
            })
        );
    }
}
