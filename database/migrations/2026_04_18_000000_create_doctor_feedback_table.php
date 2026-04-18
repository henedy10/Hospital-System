<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_feedback', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->foreignId('appointment_id')
                ->constrained('appointments')
                ->cascadeOnDelete();

            $table->tinyInteger('rating')->unsigned()->comment('1 to 5 stars');
            $table->text('comment')->nullable();
            $table->text('doctor_reply')->nullable();

            $table->timestamps();

            // One feedback per patient per appointment
            $table->unique(['patient_id', 'appointment_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_feedback');
    }
};
