<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $user = [
            'name' => 'Nurse Joy',
            'email' => 'joy@hospital.com',
            'department' => 'Emergency Room',
            'employee_id' => 'NS-77291',
            'shift' => 'Morning (08:00 - 16:00)',
            'avatar' => 'https://ui-avatars.com/api/?name=Nurse+Joy&background=0D9488&color=fff'
        ];

        return view('nurse.settings', compact('user'));
    }

    public function update(Request $request)
    {
        // Mock update logic
        return back()->with('success', 'Settings updated successfully.');
    }
}
