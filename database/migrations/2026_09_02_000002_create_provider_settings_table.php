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
        Schema::create('provider_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->json('available_days')->nullable();
            $table->time('available_from')->default('08:00:00');
            $table->time('available_to')->default('18:00:00');
            $table->boolean('is_available')->default(true);
            $table->json('offered_services')->nullable();
            $table->string('service_city')->default('مدينة غزة');
            $table->unsignedInteger('coverage_radius_km')->default(5);
            $table->unsignedInteger('commitment_score')->default(92);
            $table->unsignedInteger('punctuality_rate')->default(94);
            $table->unsignedInteger('completion_rate')->default(97);
            $table->unsignedInteger('response_rate')->default(88);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_settings');
    }
};

