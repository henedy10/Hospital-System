<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Move Data
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            if (isset($user->role) && $user->role === 'patient') {
                DB::table('patients')->insert([
                    'user_id' => $user->id,
                    'patient_id' => $user->patient_id ?? 'PAT-' . date('y') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'dob' => $user->dob ?? null,
                    'address' => $user->address ?? null,
                    'emergency_contact_name' => $user->emergency_contact_name ?? null,
                    'emergency_contact_phone' => $user->emergency_contact_phone ?? null,
                    'emergency_contact_relationship' => $user->emergency_contact_relationship ?? null,
                    'blood_type' => $user->blood_type ?? null,
                    'allergies' => $user->allergies ?? null,
                    'insurance_provider' => $user->insurance_provider ?? null,
                    'insurance_member_id' => $user->insurance_member_id ?? null,
                    'insurance_plan' => $user->insurance_plan ?? null,
                    'weight' => $user->weight ?? null,
                    'height' => $user->height ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } elseif (isset($user->role) && $user->role === 'doctor') {
                DB::table('doctors')->insert([
                    'user_id' => $user->id,
                    'specialty' => $user->specialist ?? null,
                    'bio' => $user->bio ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // 2. Drop columns safely (SQLite sometimes struggles with multiple drops or missing cols)
        if (Schema::hasColumn('users', 'patient_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Drop unique index first to prevent SQLite errors
                $table->dropUnique('users_patient_id_unique');
            });
        }

        $columnsToDrop = [
            'dob',
            'address',
            'emergency_contact_name',
            'emergency_contact_phone',
            'emergency_contact_relationship',
            'blood_type',
            'allergies',
            'insurance_provider',
            'insurance_member_id',
            'insurance_plan',
            'patient_id',
            'specialist',
            'weight',
            'height',
            'bio',
        ];

        foreach ($columnsToDrop as $col) {
            if (Schema::hasColumn('users', $col)) {
                Schema::table('users', function (Blueprint $table) use ($col) {
                    $table->dropColumn($col);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('blood_type')->nullable();
            $table->json('allergies')->nullable();
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_member_id')->nullable();
            $table->string('insurance_plan')->nullable();
            $table->string('patient_id')->nullable();
            $table->string('specialist')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->text('bio')->nullable();
        });

        // Moving data back is too complex for simple rollback, data might be lost on rollback
    }
};
