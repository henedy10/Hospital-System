<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalDoctors = User::where('role', User::ROLE_DOCTOR)->count();
        $totalNurses = User::where('role', User::ROLE_NURSE)->count();
        $totalPatients = User::where('role', User::ROLE_PATIENT)->count();

        // Recent users (last 8)
        $recentUsers = User::where('role','!=','admin')->orderByDesc('created_at')->take(8)->get();

        return view('admin.index', compact(
            'totalDoctors',
            'totalNurses',
            'totalPatients',
            'recentUsers'
        ));
    }
}
