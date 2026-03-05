<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Doctor\DashboardController;
use App\Http\Controllers\Doctor\AppointmentController;
use App\Http\Controllers\Doctor\PatientController;
use App\Http\Controllers\Doctor\ReportController;
use App\Http\Controllers\Doctor\SettingController;
use App\Http\Controllers\Nurse\DashboardController as NurseDashboardController;
use App\Http\Controllers\Nurse\PatientController as NursePatientController;
use App\Http\Controllers\Nurse\VitalsController;
use App\Http\Controllers\Nurse\TaskController as NurseTaskController;
use App\Http\Controllers\Nurse\SettingController as NurseSettingController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\MedicalHistoryController as PatientMedicalHistoryController;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'role:doctor'])->prefix('/doctor')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('doctor.dashboard');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('doctor.appointments');
    Route::get('/patients', [PatientController::class, 'index'])->name('doctor.patients');
    Route::get('/patients/{id}', [PatientController::class, 'show'])->name('doctor.patients.show');
    Route::get('/reports', [ReportController::class, 'index'])->name('doctor.reports');
    Route::get('/reports/{id}', [ReportController::class, 'show'])->name('doctor.reports.show');
    Route::get('/settings', [SettingController::class, 'index'])->name('doctor.settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('doctor.settings.update');
});

Route::middleware(['auth', 'role:nurse'])->prefix('/nurse')->group(function () {
    Route::get('/dashboard', [NurseDashboardController::class, 'index'])->name('nurse.dashboard');
    Route::get('/patients', [NursePatientController::class, 'index'])->name('nurse.patients');
    Route::get('/patients/{id}', [NursePatientController::class, 'show'])->name('nurse.patients.show');
    Route::get('/patients/{id}/vitals/create', [VitalsController::class, 'create'])->name('nurse.vitals.create');
    Route::post('/vitals', [VitalsController::class, 'store'])->name('nurse.vitals.store');
    Route::get('/tasks', [NurseTaskController::class, 'index'])->name('nurse.tasks');
    Route::get('/settings', [NurseSettingController::class, 'index'])->name('nurse.settings');
    Route::post('/settings', [NurseSettingController::class, 'update'])->name('nurse.settings.update');
});

Route::middleware(['auth', 'role:patient'])->prefix('/patient')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('patient.appointments');
    Route::get('/history', [PatientMedicalHistoryController::class, 'index'])->name('patient.history');
    Route::get('/profile', [PatientProfileController::class, 'index'])->name('patient.profile');
    Route::post('/profile', [PatientProfileController::class, 'update'])->name('patient.profile.update');
});




