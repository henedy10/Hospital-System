<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSymptomCheckRequest extends FormRequest
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
            'fever' => 'sometimes|boolean',
            'cough' => 'sometimes|boolean',
            'headache' => 'sometimes|boolean',
            'fatigue' => 'sometimes|boolean',
            'chest_pain' => 'sometimes|boolean',
            'shortness_of_breath' => 'sometimes|boolean',
            'dizziness' => 'sometimes|boolean',
            'nausea' => 'sometimes|boolean',
            'sore_throat' => 'sometimes|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'fever' => $this->has('fever') ? 1 : 0,
            'cough' => $this->has('cough') ? 1 : 0,
            'headache' => $this->has('headache') ? 1 : 0,
            'fatigue' => $this->has('fatigue') ? 1 : 0,
            'chest_pain' => $this->has('chest_pain') ? 1 : 0,
            'shortness_of_breath' => $this->has('shortness_of_breath') ? 1 : 0,
            'dizziness' => $this->has('dizziness') ? 1 : 0,
            'nausea' => $this->has('nausea') ? 1 : 0,
            'sore_throat' => $this->has('sore_throat') ? 1 : 0,
        ]);
    }
}
