<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->decimal('weight', 5, 2)->nullable()->after('respiratory_rate');
            $table->decimal('height', 5, 2)->nullable()->after('weight');
        });
    }

    public function down(): void
    {
        Schema::table('vitals', function (Blueprint $table) {
            $table->dropColumn(['weight', 'height']);
        });
    }
};
