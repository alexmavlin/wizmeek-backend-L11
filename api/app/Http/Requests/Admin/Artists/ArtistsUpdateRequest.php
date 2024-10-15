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
            "full_description" => "nullable|string|max:5000"
        ];
    }
}
