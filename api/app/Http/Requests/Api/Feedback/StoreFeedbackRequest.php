<?php

namespace App\Http\Requests\Api\Feedback;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
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
            'subject' => 'required|string|max:250',
            'message' => 'required|string|max:10000',
            'files.*' => 'file|mimes:jpg,jpeg,png,gif,svg,pdf,doc,docx,mp4,mov,avi|max:20480',
            'rating' => 'required|numeric'
        ];
    }

    public function messages(): array
    {
        return [
            'subject.required' => 'Please provide a subject for your message.',
            'subject.string' => 'The subject must be a valid text.',
            'subject.max' => 'The subject should not exceed 250 characters.',

            'message.required' => 'Your message cannot be empty.',
            'message.string' => 'The message must be valid text.',
            'message.max' => 'The message can be up to 10,000 characters long.',

            'files.*.file' => 'Each uploaded item must be a file.',
            'files.*.mimes' => 'Only images (JPG, PNG, GIF, SVG), documents (PDF, DOC, DOCX), and videos (MP4, MOV, AVI) are allowed.',
            'files.*.max' => 'Each file should not be larger than 20MB.',

            'rating.required' => 'Please provide a rating.',
            'rating.numeric' => 'The rating must be a number.',
        ];
    }
}
