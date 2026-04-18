<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isPatient();
    }

    public function rules(): array
    {
        return [
            'appointment_id' => ['required', 'integer', 'exists:appointments,id'],
            'rating'         => ['required', 'integer', 'min:1', 'max:5'],
            'comment'        => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'Please select a star rating.',
            'rating.min'      => 'Rating must be at least 1 star.',
            'rating.max'      => 'Rating cannot exceed 5 stars.',
            'comment.max'     => 'Your comment may not be longer than 1,000 characters.',
        ];
    }
}
