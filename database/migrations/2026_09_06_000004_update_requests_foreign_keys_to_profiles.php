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
            if (Schema::getConnection()->getDriverName() === 'mysql') {
                $table->dropForeign('service_requests_user_id_foreign');
                $table->dropForeign('service_requests_assigned_provider_id_foreign');
            } else {
                $table->dropForeign(['user_id']);
                $table->dropForeign(['assigned_provider_id']);
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
            $table->dropForeign(['elder_id']);
            $table->dropForeign(['provider_id']);
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
