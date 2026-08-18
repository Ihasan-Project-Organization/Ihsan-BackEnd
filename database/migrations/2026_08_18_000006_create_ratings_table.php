<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->index()
                ->constrained('requests')->cascadeOnDelete();
            $table->enum('rater_role', ['elder', 'provider']);
            $table->tinyInteger('stars');
            $table->text('comment')->nullable();
            $table->boolean('visible_to_provider')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
