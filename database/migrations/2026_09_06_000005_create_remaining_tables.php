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
        Schema::create('request_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->foreignId('reporter_id')->constrained('users')->cascadeOnDelete();
            $table->text('description');
            $table->enum('status', ['open', 'under_review', 'closed'])->default('open');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        Schema::create('volunteer_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained('service_provider_profiles')->cascadeOnDelete();
            $table->string('certificate_number')->unique();
            $table->date('issued_at');
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('type');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('volunteer_certificates');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('request_attachments');
    }
};
