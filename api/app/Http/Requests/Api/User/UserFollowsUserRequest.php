<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class UserFollowsUserRequest extends FormRequest
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
            'id' => 'required|numeric|exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'User ID is required.',
            'id.numeric'  => 'User ID must be a number.',
            'id.exists'   => 'The specified user does not exist.',
        ];
    }
}
