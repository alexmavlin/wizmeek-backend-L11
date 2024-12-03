<?php

namespace App\Http\Requests\Admin\Genres;

use Illuminate\Foundation\Http\FormRequest;

class GenresUpdateRequest extends FormRequest
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
            "color" => "nullable|string|max:7"
        ];
    }
}
