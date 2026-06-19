<?php

namespace App\Console\Commands;

use App\Models\PrescriptionItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendMedicationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-medication-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Medicine Reminder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $prescriptions = PrescriptionItem::with('prescription')->get();
        for($i = 0; $i < $prescriptions->count(); $i++) {

            if($prescriptions[$i]['created_at']->addDays($prescriptions[$i]['duration']) > now()) {

                Http::post('https://finicky-unstuffed-rewrap.ngrok-free.dev/webhook-test/2e491fe1-9a05-493f-8262-c95449414a9f', [
                    'response' => $prescriptions[$i],
                ]);
            }
            
        }

    }
}
