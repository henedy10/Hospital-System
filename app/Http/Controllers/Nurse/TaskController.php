<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = [
            'today' => [
                ['id' => 1, 'title' => 'Shift Handover', 'time' => '08:00 AM', 'status' => 'completed', 'category' => 'Administrative'],
                ['id' => 2, 'title' => 'Morning Medication Round', 'time' => '09:00 AM', 'status' => 'completed', 'category' => 'Clinical'],
                ['id' => 3, 'title' => 'Wound Care - Room 104', 'time' => '10:30 AM', 'status' => 'pending', 'category' => 'Clinical'],
                ['id' => 4, 'title' => 'Patient Assessment - Room 202', 'time' => '11:15 AM', 'status' => 'pending', 'category' => 'Clinical'],
                ['id' => 5, 'title' => 'Lunch Distribution', 'time' => '12:00 PM', 'status' => 'upcoming', 'category' => 'General'],
            ],
            'upcoming' => [
                ['id' => 6, 'title' => 'Afternoon Vitals Check', 'time' => '02:00 PM', 'status' => 'upcoming', 'category' => 'Clinical'],
                ['id' => 7, 'title' => 'Discharge Paperwork - Room 101', 'time' => '03:30 PM', 'status' => 'upcoming', 'category' => 'Administrative'],
            ]
        ];

        return view('nurse.tasks', compact('tasks'));
    }
}
