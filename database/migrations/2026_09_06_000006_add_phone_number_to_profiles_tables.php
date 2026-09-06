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
        Schema::table('elder_profiles', function (Blueprint $table) {
            $table->string('phone_number')->after('city');
        });

        Schema::table('service_provider_profiles', function (Blueprint $table) {
            $table->string('phone_number')->after('birth_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_provider_profiles', function (Blueprint $table) {
            $table->dropColumn('phone_number');
        });

        Schema::table('elder_profiles', function (Blueprint $table) {
            $table->dropColumn('phone_number');
        });
    }
};
