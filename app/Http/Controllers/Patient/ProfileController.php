<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        return view('patient.profile');
    }

    public function update(Request $request)
    {
        // Logic for updating profile
        return back()->with('success', 'Profile updated successfully');
    }
}
