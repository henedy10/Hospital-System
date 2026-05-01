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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('patient_id')->nullable()->change();
            $table->string('reason')->nullable()->change();
            // Since it's SQLite, we can't easily change enum values, but we can just use string status.
            // Or if we want to be safe with SQLite/MySQL:
            $table->string('status')->default('available')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('patient_id')->nullable(false)->change();
            $table->string('reason')->nullable(false)->change();
            $table->enum('status', ['upcoming', 'completed', 'cancelled'])->default('upcoming')->change();
        });
    }
};
