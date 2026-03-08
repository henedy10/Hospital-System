<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\Patient\ProfileUpdateRequest;
use App\Http\Requests\Patient\PasswordUpdateRequest;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('patient.profile', compact('user'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        $userData = [
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
            'phone' => $data['phone'] ?? $user->phone,
        ];

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $userData['profile_image'] = $path;
        }

        $user->update($userData);

        $patientData = [
            'dob' => $data['dob'] ?? null,
            'address' => $data['address'] ?? null,
            'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
            'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
            'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
            'blood_type' => $data['blood_type'] ?? null,
            'insurance_provider' => $data['insurance_provider'] ?? null,
            'insurance_member_id' => $data['insurance_member_id'] ?? null,
            'insurance_plan' => $data['insurance_plan'] ?? null,
            'weight' => $data['weight'] ?? null,
            'height' => $data['height'] ?? null,
        ];

        // Process allergies from string to array
        if (isset($data['allergies'])) {
            $patientData['allergies'] = array_filter(array_map('trim', explode(',', $data['allergies'])));
        }

        if ($user->patient) {
            $user->patient->update($patientData);
        } else {
            $patientData['patient_id'] = 'PAT-' . date('y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            $user->patient()->create($patientData);
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function removeImage()
    {
        $user = Auth::user();
        if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
            \Storage::disk('public')->delete($user->profile_image);
        }
        $user->update(['profile_image' => null]);
        return redirect()->back()->with('success', 'Profile image removed successfully.');
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
