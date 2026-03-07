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

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
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
