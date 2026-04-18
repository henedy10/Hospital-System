<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReplyFeedbackRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isDoctor();
    }

    public function rules(): array
    {
        return [
            'doctor_reply' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_reply.required' => 'Please write a reply before submitting.',
            'doctor_reply.max'      => 'Your reply may not be longer than 1,000 characters.',
        ];
    }
}
