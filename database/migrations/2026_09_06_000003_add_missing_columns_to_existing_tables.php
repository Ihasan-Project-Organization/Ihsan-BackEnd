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
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending')->after('email_verified_at');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->string('profile_picture_path')->nullable()->after('rejection_reason');
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->enum('pricing_type', ['volunteer', 'paid'])->default('volunteer')->after('service_type');
            $table->decimal('proposed_price', 10, 2)->nullable()->after('pricing_type');
            $table->enum('timing_type', ['immediate', 'scheduled'])->default('scheduled')->after('proposed_price');
            $table->enum('gender_preference', ['male', 'female', 'any'])->default('any')->after('timing_type');
            $table->enum('incident_type', ['apology', 'delay', 'no_show'])->nullable()->after('gender_preference');
            $table->timestamp('assigned_at')->nullable()->after('accepted_at');
        });

        Schema::table('ratings', function (Blueprint $table) {
            $table->enum('rater_role', ['elder', 'provider'])->default('elder')->after('provider_id');
            $table->boolean('visible_to_provider')->default(true)->after('comment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->dropColumn(['rater_role', 'visible_to_provider']);
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn([
                'pricing_type',
                'proposed_price',
                'timing_type',
                'gender_preference',
                'incident_type',
                'assigned_at',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'rejection_reason', 'profile_picture_path']);
        });
    }
};
