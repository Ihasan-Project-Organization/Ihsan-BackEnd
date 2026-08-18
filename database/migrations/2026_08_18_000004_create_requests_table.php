<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('elder_id')->index()
                ->constrained('elder_profiles')->cascadeOnDelete();
            $table->foreignId('provider_id')->nullable()->index()
                ->constrained('service_provider_profiles')->nullOnDelete();
            $table->enum('service_type', [
                'companionship',
                'logistics',
                'medical_accompaniment',
                'home_help',
                'tech_support',
            ]);
            $table->enum('classification', ['volunteer', 'paid']);
            $table->decimal('proposed_price', 10, 2)->nullable();
            $table->enum('timing_type', ['immediate', 'scheduled']);
            $table->dateTime('scheduled_at')->nullable();
            $table->enum('gender_preference', ['male', 'female', 'any']);
            $table->text('description')->nullable();
            $table->string('location_text');
            $table->enum('status', [
                'pending',
                'accepted',
                'in_progress',
                'completed',
                'cancelled_by_elder',
                'cancelled_by_provider',
            ])->default('pending');
            $table->text('cancelled_reason')->nullable();
            $table->timestamps();

            $table->index(['status', 'service_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
