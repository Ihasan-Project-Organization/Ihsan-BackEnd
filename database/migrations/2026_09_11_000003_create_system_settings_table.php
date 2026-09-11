<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // إدراج عتبات الترقية الافتراضية
        DB::table('system_settings')->insert([
            [
                'key' => 'tier_2_tasks_threshold',
                'value' => '10',
                'description' => 'الحد الأدنى لعدد المهام المكتملة للانتقال إلى المستوى الثاني (Tier 2)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'tier_2_rating_threshold',
                'value' => '4.0',
                'description' => 'الحد الأدنى لمتوسط التقييم للانتقال إلى المستوى الثاني (Tier 2)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'tier_3_tasks_threshold',
                'value' => '30',
                'description' => 'الحد الأدنى لعدد المهام المكتملة للانتقال إلى المستوى الثالث (Tier 3)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'tier_3_rating_threshold',
                'value' => '4.3',
                'description' => 'الحد الأدنى لمتوسط التقييم للانتقال إلى المستوى الثالث (Tier 3)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
