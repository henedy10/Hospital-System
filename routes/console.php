<?php

use App\Models\Appointment;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $from = now()->addHours(2)->subMinutes(10)->format('H:i');
    $to = now()->addHours(2)->addMinutes(10)->format('H:i');
    $appointments = Appointment::with('patient.user', 'doctor.user')
        ->where('status', 'upcoming')
        ->where('is_reminder', false)
        ->whereDate('appointment_date', now()->toDateString())
        ->whereBetween('appointment_time', [$from, $to])
        ->get();

    if (count($appointments) > 0) {
        foreach ($appointments as $appointment) {
            try {
                Http::post('https://finicky-unstuffed-rewrap.ngrok-free.dev/webhook-test/740a65d6-2c5c-49c3-b928-7c7e874aa5ca', [
                    'appointments' => $appointment,
                ]);

                $appointment->update([
                    'is_reminder' => true,
                ]);

            } catch (\Exception $e) {
                Log::error('Webhook failed', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

    } else {
        Http::post('https://finicky-unstuffed-rewrap.ngrok-free.dev/webhook-test/740a65d6-2c5c-49c3-b928-7c7e874aa5ca', [
            'response' => 'no appointments found',
        ]);
    }
})->everyMinute();
