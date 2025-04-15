<?php

namespace App\Http\Requests\Admin\Genres;

use Illuminate\Foundation\Http\FormRequest;

class GenresCreateRequest extends FormRequest
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
            "genre" => "required|string|max:100",
            "color" => "nullable|string|max:7|regex:/^#[0-9a-fA-F]{6}$/",
            "image" => "nullable|image|mimes:jpg,jpeg,gif,png,heif,heic|max:5120"
        ];
    }

    public function messages(): array
    {
        return [
            'genre.required' => 'The genre field is required.',
            'genre.string' => 'The genre must be a valid string.',
            'genre.max' => 'The genre may not be greater than 100 characters.',

            'color.string' => 'The color must be a valid string.',
            'color.max' => 'The color may not be longer than 7 characters.',
            'color.regex' => 'The color must be a valid hex code (e.g. #FF00FF).',

            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpg, jpeg, gif, png, heif, or heic.',
            'image.max' => 'The image may not be greater than 5MB.',
        ];
    }
}
