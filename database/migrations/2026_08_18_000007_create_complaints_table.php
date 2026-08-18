<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->index()
                ->constrained('requests')->cascadeOnDelete();
            $table->foreignId('reporter_id')->index()
                ->constrained('users')->cascadeOnDelete();
            $table->text('description');
            $table->enum('status', ['open', 'under_review', 'closed'])->default('open');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
