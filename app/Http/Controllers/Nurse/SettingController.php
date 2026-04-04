<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $authUser = Auth::user();

        $user = [
            'id' => $authUser->id,
            'name' => $authUser->name,
            'email' => $authUser->email,
            'phone' => $authUser->phone,
            'department' => $authUser->department ?? 'General Ward',
            'employee_id' => 'NS-' . str_pad($authUser->id, 5, '0', STR_PAD_LEFT),
            'shift' => $authUser->shift ?? 'Morning (08:00 - 16:00)',
            'speciality' => optional($authUser->nurse)->speciality,
            'bio' => optional($authUser->nurse)->bio,
            'avatar' => $authUser->profile_image ? asset('storage/' . $authUser->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($authUser->name) . '&background=0D9488&color=fff'
        ];

        return view('nurse.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'shift' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'speciality' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
        ]);

        if ($request->hasFile('profile_image')) {
            // Delete old image if exists
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profiles', 'public');
            $validated['profile_image'] = $path;
        }

        $user->update($validated);

        // Update or create nurse profile
        $user->nurse()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'speciality' => $request->speciality,
                'bio' => $request->bio,
            ]
        );

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match our records.']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
