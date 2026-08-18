<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['elder', 'provider', 'admin', 'super_admin'])
                ->default('elder')
                ->after('account_type');
            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])
                ->default('pending')
                ->after('role');
            $table->text('rejection_reason')->nullable()->after('status');
            $table->string('profile_picture_path')->nullable()->after('rejection_reason');
        });

        DB::table('users')
            ->where('account_type', 'volunteer')
            ->update(['role' => 'provider']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'status',
                'rejection_reason',
                'profile_picture_path',
            ]);
        });
    }
};
