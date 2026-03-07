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
        Schema::table('users', function (Blueprint $table) {
            $table->date('dob')->nullable()->after('phone');
            $table->text('address')->nullable()->after('dob');
            $table->string('emergency_contact_name')->nullable()->after('address');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            $table->string('blood_type')->nullable()->after('emergency_contact_relationship');
            $table->text('allergies')->nullable()->after('blood_type');
            $table->string('insurance_provider')->nullable()->after('allergies');
            $table->string('insurance_member_id')->nullable()->after('insurance_provider');
            $table->string('insurance_plan')->nullable()->after('insurance_member_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'dob',
                'address',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
                'blood_type',
                'allergies',
                'insurance_provider',
                'insurance_member_id',
                'insurance_plan'
            ]);
        });
    }
};
