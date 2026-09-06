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
        Schema::table('ratings', function (Blueprint $table) {
            $table->unique(['service_request_id', 'rater_role'], 'ratings_request_rater_unique');
            $table->dropUnique('service_reviews_service_request_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->unique('service_request_id', 'service_reviews_service_request_id_unique');
            $table->dropUnique('ratings_request_rater_unique');
        });
    }
};
