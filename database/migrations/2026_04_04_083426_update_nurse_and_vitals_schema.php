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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['department', 'shift']);
        });

        Schema::table('nurses', function (Blueprint $table) {
            $table->string('department')->nullable()->after('speciality');
            $table->string('shift')->nullable()->after('department');
        });

        Schema::table('vitals', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('spo2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->dropColumn('notes');
        });

        Schema::table('nurses', function (Blueprint $table) {
            $table->dropColumn(['department', 'shift']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('department')->nullable()->after('role');
            $table->string('shift')->nullable()->after('department');
        });
    }
};
