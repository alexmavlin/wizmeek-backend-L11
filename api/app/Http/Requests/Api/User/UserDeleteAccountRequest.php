<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserDeleteAccountRequest extends FormRequest
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
            'user_id' => 'required|numeric|exists:users,id',
            'email' => [
                'required',
                'string',
                Rule::exists('users', 'email')->where(function ($query) {
                    $userId = $this->input('user_id');
                    $query->where('id', $userId);
                }),
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'The user ID is required.',
            'user_id.numeric' => 'The user ID must be a number.',
            'user_id.exists' => 'The selected user ID does not exist.',

            'email.required' => 'The email address is required.',
            'email.string' => 'The email must be a valid string.',
            'email.exists' => 'The email does not match the given user ID.',
        ];
    }
}
