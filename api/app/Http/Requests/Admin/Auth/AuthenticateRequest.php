<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticateRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'The email field is required. Please provide your email address.',
            'email.email' => 'Please enter a valid email address in the format: example@domain.com.',
            'password.required' => 'The password field is required. Please enter your password.',
            'password.string' => 'The password must be a valid string. Ensure it does not contain invalid characters.',
        ];
    }
}
