<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    protected $errorBag = 'profileUpdate';

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
        $user = $this->user();
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|regex:/^01[0125][0-9]{8}$/',
            'dob' => 'nullable|date',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|regex:/^01[0125][0-9]{8}$/',
            'emergency_contact_relationship' => 'nullable|string|max:255',
            'blood_type' => 'nullable|string|max:10',
            'allergies' => 'nullable|string',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_member_id' => 'nullable|string|max:255',
            'insurance_plan' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'weight' => 'nullable|numeric|between:0,999.99',
            'height' => 'nullable|numeric|between:0,999.99',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'The phone number must be a valid Egyptian mobile number (e.g., 01012345678).',
            'emergency_contact_phone.regex' => 'The emergency contact phone must be a valid Egyptian mobile number (e.g., 01012345678).',
            'profile_image.max' => 'The profile image must be less than 2MB.',
            'profile_image.mimes' => 'The profile image must be a valid image file (jpeg, png, jpg, gif).',
            'profile_image.image' => 'The profile image must be a valid image file (jpeg, png, jpg, gif).'
        ];
    }
}
