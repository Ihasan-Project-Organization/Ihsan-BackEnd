<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * تهيئة قاعدة البيانات وإنشاء الحساب المعتمد الوحيد: مدير النظام الأعلى (Super Admin).
     * يمنع إنشاء أي حسابات تجريبية أخرى وفقاً لدستور المشروع.
     */
    public function run(): void
    {
        // 1. حساب مدير النظام الأعلى (Super Admin) - الحساب التجريبي الوحيد المصرح به
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@anees.com'],
            [
                'name' => 'مدير النظام الأعلى',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'approved',
                'rejection_reason' => null,
                'suspension_reason' => null,
                'resubmission_note' => null,
            ]
        );

        Admin::updateOrCreate(
            ['user_id' => $superAdmin->id],
            [
                'admin_level' => 'super_admin',
            ]
        );

        // 2. إعدادات النظام الافتراضية المعتمدة في المرجع (§6 و §10)
        SystemSetting::set('tier_2_tasks_threshold', 10, 'integer');
        SystemSetting::set('tier_2_rating_threshold', 4.0, 'float');
        SystemSetting::set('tier_3_tasks_threshold', 30, 'integer');
        SystemSetting::set('tier_3_rating_threshold', 4.3, 'float');
        SystemSetting::set('reliability_incidents_threshold', 3, 'integer');
    }
}
