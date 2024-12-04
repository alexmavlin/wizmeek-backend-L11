<?php

namespace App\Http\Requests\Admin\Videos;

use Illuminate\Foundation\Http\FormRequest;

class StoreYouTubeVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "original_link" => "required|string|unique:you_tube_videos,original_link",
            "youtube_id" => "required|string|max:50|unique:you_tube_videos,youtube_id",
            "content_type_id" => "required|string",
            "artist_id" => "nullable|string",
            "artist_name" => "required|string|max:150",
            "title" => "required|string|max:150",
            "thumbnail" => "required|string",
            "release_date" => "required|string",
            "genre_id" => "required|string",
            "country_id" => "required|string",
            "editors_pick" => "nullable|string",
            "new" => "nullable|string",
            "throwback" => "nullable|string",
            "video_type" => "required|string"
        ];
    }
}
