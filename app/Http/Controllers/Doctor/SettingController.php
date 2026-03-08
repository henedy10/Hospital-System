<?php

namespace App\Http\Controllers\Doctor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Doctor\ProfileUpdateRequest;
use App\Http\Requests\Doctor\PasswordUpdateRequest;

class SettingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('doctor.settings.index', compact('user'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = auth()->user();
        $data = $request->validated();

        $userData = [
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ];

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
                \Storage::disk('public')->delete($user->profile_image);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $userData['profile_image'] = $path;
        }

        $user->update($userData);

        $doctorData = [
            'specialty' => $data['specialist'] ?? null,
            'bio' => $data['bio'] ?? null,
        ];

        if ($user->doctor) {
            $user->doctor->update($doctorData);
        } else {
            $user->doctor()->create($doctorData);
        }

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function removeImage()
    {
        $user = auth()->user();
        if ($user->profile_image && \Storage::disk('public')->exists($user->profile_image)) {
            \Storage::disk('public')->delete($user->profile_image);
        }
        $user->update(['profile_image' => null]);
        return redirect()->back()->with('success', 'Profile image removed successfully.');
    }

    public function updatePassword(PasswordUpdateRequest $request)
    {
        $user = auth()->user();
        $user->update([
            'password' => \Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully');
    }

    public function updateNotifications(Request $request)
    {
        $user = auth()->user();
        $user->update([
            'notification_settings' => [
                'email' => $request->has('email'),
                'sms' => $request->has('sms'),
                'reports' => $request->has('reports'),
                'appointments' => $request->has('appointments'),
                'system' => $request->has('system'),
            ]
        ]);

        return back()->with('success', 'Notification settings updated successfully');
    }
}
