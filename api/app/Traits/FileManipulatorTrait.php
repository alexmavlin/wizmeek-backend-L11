<?

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait FileManipulatorTrait
{
    /**
     * Uploads an artist's avatar and returns the file path.
     *
     * This method generates a unique filename, moves the uploaded avatar file
     * to the designated public directory, and returns the relative file path.
     *
     * @param \Illuminate\Http\UploadedFile $avatar The uploaded avatar file.
     * @return string The relative file path of the stored avatar.
     */
    public function uploadArtistAvatar($avatar): string
    {
        $fileName = uniqid() . '.' . $avatar->getClientOriginalExtension();
        $destinationPath = public_path('img/artists/avatars');
        $avatar->move($destinationPath, $fileName);
        $filePath = 'img/artists/avatars/' . $fileName;

        return $filePath;
    }

    /**
     * Updates an artist's avatar by deleting the old file (if it exists) and uploading a new one.
     *
     * This method checks if the artist already has an avatar, deletes the existing file if present,
     * then uploads the new avatar and returns its file path.
     *
     * @param \App\Models\Artist $artist The artist whose avatar is being updated.
     * @param \Illuminate\Http\UploadedFile $newAvatar The new avatar file.
     * @return string The relative file path of the updated avatar.
     */
    public function updateArtistAvatar($artist, $newAvatar): string
    {
        if ($artist->avatar && File::exists(public_path($artist->avatar))) {
            File::delete(public_path($artist->avatar));
        }
        $fileName = uniqid() . '.' . $newAvatar->getClientOriginalExtension();
        $destinationPath = public_path('img/artists/avatars');
        $newAvatar->move($destinationPath, $fileName);
        $filePath = 'img/artists/avatars/' . $fileName;

        return $filePath;
    }
}
