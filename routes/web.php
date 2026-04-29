<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\
{
    DashboardController as AdminDashboardController,
    UserController as AdminUserController,
    AppointmentController as AdminAppointmentController
};

use App\Http\Controllers\Doctor\
{
    DashboardController,
    AppointmentController,
    PatientController,
    MedicalHistoryController as DoctorMedicalHistoryController,
    ReportController,
    SettingController,
    TaskController as DoctorTaskController,
    FeedbackController as DoctorFeedbackController,
    PrescriptionController as DoctorPrescriptionController
};

use App\Http\Controllers\Nurse\
{
    DashboardController as NurseDashboardController,
    PatientController as NursePatientController,
    VitalsController,
    TaskController as NurseTaskController,
    SettingController as NurseSettingController,
};

use App\Http\Controllers\Patient\
{
    DashboardController as PatientDashboardController,
    AppointmentController as PatientAppointmentController,
    MedicalHistoryController as PatientMedicalHistoryController,
    ProfileController as PatientProfileController,
    FeedbackController as PatientFeedbackController,
    PrescriptionController as PatientPrescriptionController
};

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\SymptomCheckController;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/specialty/{specialty}', [HomeController::class, 'showSpecialty'])->name('specialty.show');

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

Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    Route::get('/chat', \App\Livewire\Chat::class)->name('chat');
});


Route::middleware(['auth', 'role:doctor'])->prefix('/doctor')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('doctor.dashboard');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('doctor.appointments');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('doctor.appointments.update-status');
    Route::get('/patients', [PatientController::class, 'index'])->name('doctor.patients');
    Route::get('/patients/{id}', [PatientController::class, 'show'])->name('doctor.patients.show');

    // Medical History
    Route::post('/medical-history', [DoctorMedicalHistoryController::class, 'store'])->name('doctor.medical-history.store');
    Route::put('/medical-history/{history}', [DoctorMedicalHistoryController::class, 'update'])->name('doctor.medical-history.update');
    Route::delete('/medical-history/{history}', [DoctorMedicalHistoryController::class, 'destroy'])->name('doctor.medical-history.destroy');
    Route::get('/reports', [ReportController::class, 'index'])->name('doctor.reports');
    Route::get('/reports/{history}', [ReportController::class, 'show'])->name('doctor.reports.show');
    Route::get('/settings', [SettingController::class, 'index'])->name('doctor.settings');
    Route::post('/settings', [SettingController::class, 'update'])->name('doctor.settings.update');
    Route::delete('/settings/image', [SettingController::class, 'removeImage'])->name('doctor.settings.image.remove');
    Route::post('/settings/password', [SettingController::class, 'updatePassword'])->name('doctor.settings.password');
    Route::post('/settings/notifications', [SettingController::class, 'updateNotifications'])->name('doctor.settings.notifications');

    // Task management (assign to nurses)
    Route::get('/tasks', [DoctorTaskController::class, 'index'])->name('doctor.tasks.index');
    Route::get('/tasks/create', [DoctorTaskController::class, 'create'])->name('doctor.tasks.create');
    Route::post('/tasks', [DoctorTaskController::class, 'store'])->name('doctor.tasks.store');
    Route::delete('/tasks/{task}', [DoctorTaskController::class, 'destroy'])->name('doctor.tasks.destroy');

    // Doctor Reviews
    Route::get('/reviews', [DoctorFeedbackController::class, 'index'])->name('doctor.reviews.index');
    Route::post('/reviews/{feedback}/reply', [DoctorFeedbackController::class, 'reply'])->name('doctor.reviews.reply');

    // Prescriptions
    Route::get('/prescriptions', [DoctorPrescriptionController::class, 'index'])->name('doctor.prescriptions.index');
    Route::get('/prescriptions/create', [DoctorPrescriptionController::class, 'create'])->name('doctor.prescriptions.create');
    Route::post('/prescriptions', [DoctorPrescriptionController::class, 'store'])->name('doctor.prescriptions.store');
    Route::get('/prescriptions/{prescription}', [DoctorPrescriptionController::class, 'show'])->name('doctor.prescriptions.show');
    Route::delete('/prescriptions/{prescription}', [DoctorPrescriptionController::class, 'destroy'])->name('doctor.prescriptions.destroy');
});

Route::middleware(['auth', 'role:nurse'])->prefix('/nurse')->group(function () {
    Route::get('/dashboard', [NurseDashboardController::class, 'index'])->name('nurse.dashboard');
    Route::get('/patients', [NursePatientController::class, 'index'])->name('nurse.patients');
    Route::get('/patients/{id}', [NursePatientController::class, 'show'])->name('nurse.patients.show');
    Route::patch('/patients/{id}/status', [NursePatientController::class, 'updateStatus'])->name('nurse.patients.update-status');
    Route::get('/patients/{id}/vitals/create', [VitalsController::class, 'create'])->name('nurse.vitals.create');
    Route::post('/vitals', [VitalsController::class, 'store'])->name('nurse.vitals.store');
    Route::get('/tasks', [NurseTaskController::class, 'index'])->name('nurse.tasks');
    Route::patch('/tasks/{task}/status', [NurseTaskController::class, 'updateStatus'])->name('nurse.tasks.update-status');
    Route::get('/settings', [NurseSettingController::class, 'index'])->name('nurse.settings');
    Route::post('/settings', [NurseSettingController::class, 'update'])->name('nurse.settings.update');
    Route::post('/settings/password', [NurseSettingController::class, 'updatePassword'])->name('nurse.settings.password');
});

Route::middleware(['auth', 'role:patient'])->prefix('/patient')->group(function () {
    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
    Route::get('/appointments', [PatientAppointmentController::class, 'index'])->name('patient.appointments');
    Route::post('/appointments', [PatientAppointmentController::class, 'store'])->name('patient.appointments.store');
    Route::put('/appointments/{appointment}', [PatientAppointmentController::class, 'update'])->name('patient.appointments.update');
    Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('patient.appointments.cancel');
    Route::get('/history', [PatientMedicalHistoryController::class, 'index'])->name('patient.history');
    Route::get('/history/{history}', [PatientMedicalHistoryController::class, 'show'])->name('patient.history.show');
    Route::get('/profile', [PatientProfileController::class, 'index'])->name('patient.profile');
    Route::post('/profile', [PatientProfileController::class, 'update'])->name('patient.profile.update');
    Route::delete('/profile/image', [PatientProfileController::class, 'removeImage'])->name('patient.profile.image.remove');
    Route::post('/profile/password', [PatientProfileController::class, 'updatePassword'])->name('patient.profile.password');

    // AI Symptom Checker
    Route::get('/symptoms', [SymptomCheckController::class, 'index'])->name('symptoms.index');
    Route::post('/symptoms/analyze', [SymptomCheckController::class, 'analyze'])->name('symptoms.analyze');
    Route::get('/symptoms/result/{id}', [SymptomCheckController::class, 'result'])->name('symptoms.result');
    Route::get('/symptoms/history', [SymptomCheckController::class, 'history'])->name('symptoms.history');

    // AI Chatbot
    Route::get('/ai-chat', [ChatbotController::class, 'index'])->name('patient.ai-chat');
    Route::post('/ai-chat/send', [ChatbotController::class, 'sendMessage'])->name('patient.ai-chat.send');

    // Doctor Feedback
    Route::post('/feedback', [PatientFeedbackController::class, 'store'])->name('patient.feedback.store');
    Route::put('/feedback/{feedback}', [PatientFeedbackController::class, 'update'])->name('patient.feedback.update');
    Route::delete('/feedback/{feedback}', [PatientFeedbackController::class, 'destroy'])->name('patient.feedback.destroy');

    // Prescriptions
    Route::get('/prescriptions', [PatientPrescriptionController::class, 'index'])->name('patient.prescriptions.index');
    Route::get('/prescriptions/{prescription}', [PatientPrescriptionController::class, 'show'])->name('patient.prescriptions.show');
    Route::get('/prescriptions/{prescription}/explain', [PatientPrescriptionController::class, 'explain'])->name('patient.prescriptions.explain');
});


Route::middleware(['auth', 'role:admin'])->prefix('/admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // User management
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    // Appointments overview
    Route::get('/appointments', [AdminAppointmentController::class, 'index'])->name('admin.appointments');
});
