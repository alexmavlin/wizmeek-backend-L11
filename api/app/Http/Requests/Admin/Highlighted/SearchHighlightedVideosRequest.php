<?php

namespace App\Http\Requests\Admin\Highlighted;

use Illuminate\Foundation\Http\FormRequest;

class SearchHighlightedVideosRequest extends FormRequest
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
            'searchString' => 'nullable|string|max:70'
        ];
    }
}
