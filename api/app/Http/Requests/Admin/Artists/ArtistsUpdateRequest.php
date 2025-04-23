<?php

namespace App\Http\Requests\Admin\Artists;

use Illuminate\Foundation\Http\FormRequest;

class ArtistsUpdateRequest extends FormRequest
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
            "avatar" => "nullable|image|mimes:jpg,jpeg,png,webp",
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
            'name.required' => 'The name is required.',
            'name.string' => 'The name must be a valid string.',
            'name.max' => 'The name must not exceed 200 characters.',

            'avatar.image' => 'The avatar must be an image.',
            'avatar.mimes' => 'The avatar must be a file of type: jpg, jpeg, png, or webp.',

            'short_description.string' => 'The short description must be a string.',
            'short_description.max' => 'The short description must not exceed 500 characters.',

            'full_description.string' => 'The full description must be a string.',
            'full_description.max' => 'The full description must not exceed 5000 characters.',

            'spotify_link.string' => 'The Spotify link must be a string.',
            'spotify_link.max' => 'The Spotify link must not exceed 250 characters.',

            'apple_music_link.string' => 'The Apple Music link must be a string.',
            'apple_music_link.max' => 'The Apple Music link must not exceed 250 characters.',

            'instagram_link.string' => 'The Instagram link must be a string.',
            'instagram_link.max' => 'The Instagram link must not exceed 250 characters.',

            'genres.required' => 'At least one genre must be selected.',
            'genres.array' => 'The genres field must be an array.',
            'genres.*.required' => 'Each genre is required.',
            'genres.*.numeric' => 'Each genre must be a valid numeric ID.',

            'countries.required' => 'At least one country must be selected.',
            'countries.array' => 'The countries field must be an array.',
            'countries.*.required' => 'Each country is required.',
            'countries.*.numeric' => 'Each country must be a valid numeric ID.',
        ];
    }
}
