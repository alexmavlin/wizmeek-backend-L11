<?php

namespace App\Http\Requests\Admin\Videos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StoreYouTubeVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /* public function failedValidation(Validator $validator)
    {
        dd($validator->errors()->all());
        throw new ValidationException($validator);
    } */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // dd($this->all());
        return [
            "original_link" => "required|string",
            "youtube_id" => "required|string|max:50",
            "content_type_id" => "required|string",
            "artist_id" => "nullable|string",
            "artist_name" => "required|string|max:150",
            "title" => "required|string|max:150",
            "spotify_link" => "nullable|string|max:1000",
            "apple_music_link" => "nullable|string|max:1000",
            "thumbnail" => "required|string",
            "release_date" => "required|string",
            "genre_id" => "required|string",
            "country_id" => "required|string",
            "editors_pick" => "nullable|string",
            "new" => "nullable|string",
            "throwback" => "nullable|string",
            "is_draft" => "nullable|string"
        ];
    }
}
