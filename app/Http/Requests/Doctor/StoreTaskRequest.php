<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isDoctor();
    }

    public function rules(): array
    {
        return [
            'nurse_id'    => ['required', 'exists:users,id'],
            'patient_id'  => ['nullable', 'exists:patients,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'notes'       => ['nullable', 'string', 'max:2000'],
            'category'    => ['required', 'in:General,Clinical,Administrative'],
            'priority'    => ['required', 'in:Low,Medium,High'],
            'due_at'      => ['required', 'date', 'after:now'],
        ];
    }

    public function messages(): array
    {
        return [
            'nurse_id.required' => 'Please select a nurse to assign this task to.',
            'due_at.after'      => 'The due date must be in the future.',
        ];
    }
}
