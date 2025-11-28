<?php

namespace App\Http\Requests\Admin\Artists;

use Illuminate\Foundation\Http\FormRequest;

class ArtistsStoreRequest extends FormRequest
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
            "name" => "required|string|max:200",
            "avatar" => "required|image|mimes:jpg,jpeg,png,webp",
            "short_description" => "nullable|string|max:500",
            "full_description" => "nullable|string|max:5000",
            "is_visible" => "nullable",
            "spotify_link" => "nullable|string|max:250",
            "apple_music_link" => "nullable|string|max:250",
            "instagram_link" => "nullable|string|max:250",
            "genres" => "required|array|min:1",
            "genres.*" => "required|numeric",
            "countries" => "required|array|min:1",
            "countries.*" => "required|numeric",
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'name.max' => 'The name may not be greater than 200 characters.',

            'avatar.required' => 'An avatar is required.',
            'avatar.image' => 'The avatar must be an image.',
            'avatar.mimes' => 'The avatar must be a file of type: jpg, jpeg, png, webp.',

            'short_description.string' => 'The short description must be a string.',
            'short_description.max' => 'The short description may not be greater than 500 characters.',

            'full_description.string' => 'The full description must be a string.',
            'full_description.max' => 'The full description may not be greater than 5000 characters.',

            'spotify_link.string' => 'The Spotify link must be a string.',
            'spotify_link.max' => 'The Spotify link may not be greater than 250 characters.',

            'apple_music_link.string' => 'The Apple Music link must be a string.',
            'apple_music_link.max' => 'The Apple Music link may not be greater than 250 characters.',

            'instagram_link.string' => 'The Instagram link must be a string.',
            'instagram_link.max' => 'The Instagram link may not be greater than 250 characters.',

            'genres.required' => 'At least one genre must be selected.',
            'genres.*.required' => 'Each genre is required.',
            'genres.*.numeric' => 'Each genre must be a valid ID.',

            'countries.required' => 'At least one country must be selected.',
            'countries.*.required' => 'Each country is required.',
            'countries.*.numeric' => 'Each country must be a valid ID.',
        ];
    }
}
