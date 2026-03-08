<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('doctor_id')->nullable()->after('user_id')->constrained('users')->onDelete('cascade');
        });

        // Optional: Populate doctor_id for existing appointments
        $appointments = DB::table('appointments')->get();
        foreach ($appointments as $appointment) {
            $doctor = DB::table('users')->where('name', $appointment->doctor_name)->first();
            if ($doctor) {
                DB::table('appointments')->where('id', $appointment->id)->update(['doctor_id' => $doctor->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');
        });
    }
};
