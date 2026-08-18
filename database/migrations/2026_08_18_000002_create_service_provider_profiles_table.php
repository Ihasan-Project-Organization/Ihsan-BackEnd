<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->date('birth_date');
            $table->string('id_document_path');
            $table->string('good_conduct_cert_path');
            $table->tinyInteger('tier')->default(1);
            $table->integer('completed_tasks_count')->default(0);
            $table->decimal('average_rating', 2, 1)->nullable()->default(null);
            $table->boolean('is_available')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_provider_profiles');
    }
};
