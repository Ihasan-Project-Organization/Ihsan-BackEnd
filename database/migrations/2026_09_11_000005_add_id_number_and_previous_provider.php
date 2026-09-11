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
        Schema::table('elder_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('elder_profiles', 'id_number')) {
                $table->string('id_number', 50)->nullable()->after('full_name');
            }
            if (! Schema::hasColumn('elder_profiles', 'birth_date')) {
                $table->date('birth_date')->nullable()->after('id_number');
            }
            if (! Schema::hasColumn('elder_profiles', 'address')) {
                $table->string('address')->nullable()->after('city');
            }
            if (! Schema::hasColumn('elder_profiles', 'housing_type')) {
                $table->string('housing_type', 50)->nullable()->after('address');
            }
        });

        Schema::table('service_provider_profiles', function (Blueprint $table) {
            if (! Schema::hasColumn('service_provider_profiles', 'id_number')) {
                $table->string('id_number', 50)->nullable()->after('full_name');
            }
        });

        Schema::table('requests', function (Blueprint $table) {
            if (! Schema::hasColumn('requests', 'previous_provider_id')) {
                $table->foreignId('previous_provider_id')->nullable()->after('provider_id')->constrained('service_provider_profiles')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            if (Schema::hasColumn('requests', 'previous_provider_id')) {
                $table->dropForeign(['previous_provider_id']);
                $table->dropColumn('previous_provider_id');
            }
        });

        Schema::table('service_provider_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('service_provider_profiles', 'id_number')) {
                $table->dropColumn('id_number');
            }
        });

        Schema::table('elder_profiles', function (Blueprint $table) {
            $cols = array_filter(['id_number', 'birth_date', 'address', 'housing_type'], fn ($col) => Schema::hasColumn('elder_profiles', $col));
            if (! empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
