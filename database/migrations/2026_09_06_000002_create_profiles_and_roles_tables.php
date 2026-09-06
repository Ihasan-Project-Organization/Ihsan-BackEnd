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
        Schema::create('elder_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('city');
            $table->string('id_document_path')->nullable();
            $table->timestamps();
        });

        Schema::create('service_provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('full_name');
            $table->date('birth_date');
            $table->string('id_document_path');
            $table->string('good_conduct_cert_path');
            $table->tinyInteger('tier')->default(1);
            $table->integer('completed_tasks_count')->default(0);
            $table->decimal('average_rating', 2, 1)->nullable();
            $table->boolean('is_available')->default(false);
            $table->integer('reliability_incidents_count')->default(0);
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->enum('admin_level', ['admin', 'super_admin']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
        Schema::dropIfExists('service_provider_profiles');
        Schema::dropIfExists('elder_profiles');
    }
};
