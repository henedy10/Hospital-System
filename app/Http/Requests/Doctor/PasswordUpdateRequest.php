<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class PasswordUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->isDoctor();
    }

    public function rules(): array
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }
}
