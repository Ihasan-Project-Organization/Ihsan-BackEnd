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
        Schema::rename('service_requests', 'requests');
        Schema::rename('service_reviews', 'ratings');
        Schema::table('ratings', function (Blueprint $table) {
            $table->renameColumn('rating', 'stars');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ratings', function (Blueprint $table) {
            $table->renameColumn('stars', 'rating');
        });
        Schema::rename('ratings', 'service_reviews');
        Schema::rename('requests', 'service_requests');
    }
};
