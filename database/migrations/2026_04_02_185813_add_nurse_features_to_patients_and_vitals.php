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
        Schema::table('patients', function (Blueprint $table) {
            $table->string('status')->default('Stable')->after('nurse_id');
        });

        Schema::table('vitals', function (Blueprint $table) {
            $table->foreignId('recorded_by')->nullable()->after('respiratory_rate')->constrained('nurses')->onDelete('set null');
            $table->integer('spo2')->nullable()->after('recorded_by'); // I saw spo2 used in controller but not in migration earlier
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('vitals', function (Blueprint $table) {
            $table->dropForeign(['recorded_by']);
            $table->dropColumn(['recorded_by', 'spo2']);
        });
    }
};
