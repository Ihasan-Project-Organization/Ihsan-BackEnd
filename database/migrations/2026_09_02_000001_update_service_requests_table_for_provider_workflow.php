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
        Schema::table('service_requests', function (Blueprint $table) {
            $table->string('service_type')->default('grocery')->after('title');
            $table->string('district')->nullable()->after('location');
            $table->decimal('distance_km', 4, 1)->default(2.5)->after('district');
            $table->dateTime('on_the_way_at')->nullable()->after('accepted_at');
            $table->dateTime('arrived_at')->nullable()->after('on_the_way_at');
            $table->dateTime('delay_reported_at')->nullable()->after('started_at');
            $table->dateTime('expected_arrival_at')->nullable()->after('delay_reported_at');
            $table->string('delay_reason')->nullable()->after('expected_arrival_at');
            $table->text('completion_notes')->nullable()->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_requests', function (Blueprint $table) {
            $table->dropColumn([
                'service_type',
                'district',
                'distance_km',
                'on_the_way_at',
                'arrived_at',
                'delay_reported_at',
                'expected_arrival_at',
                'delay_reason',
                'completion_notes',
            ]);
        });
    }
};

