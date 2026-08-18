<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->index()
                ->constrained('service_provider_profiles')->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->date('issued_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_certificates');
    }
};
