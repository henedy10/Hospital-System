<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('symptom_checks', function (Blueprint $table) {
            // Rename/Change existing columns
            $table->renameColumn('symptoms_text', 'symptoms_json'); // We will store as JSON string
            $table->renameColumn('urgency_level', 'urgency');
            
            // Add new columns
            $table->string('predicted_disease')->after('symptoms_json')->nullable();
            $table->string('specialization')->after('predicted_disease')->nullable();
            
            // Drop old column if it exists and isn't needed
            // $table->dropColumn('ai_response'); 
        });

        // Ensure symptoms_json is actually JSON type if supported, otherwise stay text
        Schema::table('symptom_checks', function (Blueprint $table) {
             $table->json('symptoms_json')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('symptom_checks', function (Blueprint $table) {
            $table->renameColumn('symptoms_json', 'symptoms_text');
            $table->renameColumn('urgency', 'urgency_level');
            $table->dropColumn(['predicted_disease', 'specialization']);
        });
    }
};
