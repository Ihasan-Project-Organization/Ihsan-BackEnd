<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('date_of_birth')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('identity_number')->nullable()->unique();
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('housing_type')->nullable();
            $table->text('extra_info')->nullable();
            $table->string('identity_document_path')->nullable();
            $table->string('conduct_document_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_profiles');
    }
};
