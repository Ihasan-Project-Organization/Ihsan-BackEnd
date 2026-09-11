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
        Schema::table('requests', function (Blueprint $table) {
            $driver = Schema::getConnection()->getDriverName();
            if ($driver === 'sqlite') {
                return;
            }

            // في كل من MySQL و PostgreSQL، تحتفظ المفاتيح الأجنبية بالاسم القديم قبل إعادة تسمية الجدول
            try {
                $table->dropForeign('service_requests_user_id_foreign');
            } catch (\Throwable) {
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Throwable) {}
            }

            try {
                $table->dropForeign('service_requests_assigned_provider_id_foreign');
            } catch (\Throwable) {
                try {
                    $table->dropForeign(['assigned_provider_id']);
                } catch (\Throwable) {}
            }
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->renameColumn('user_id', 'elder_id');
            $table->renameColumn('assigned_provider_id', 'provider_id');
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->foreign('elder_id')->references('id')->on('elder_profiles')->cascadeOnDelete();
            $table->foreign('provider_id')->references('id')->on('service_provider_profiles')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $driver = Schema::getConnection()->getDriverName();
            if ($driver === 'sqlite') {
                return;
            }

            try {
                $table->dropForeign(['elder_id']);
            } catch (\Throwable) {}

            try {
                $table->dropForeign(['provider_id']);
            } catch (\Throwable) {}
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->renameColumn('elder_id', 'user_id');
            $table->renameColumn('provider_id', 'assigned_provider_id');
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('assigned_provider_id')->references('id')->on('users')->nullOnDelete();
        });
    }
};
