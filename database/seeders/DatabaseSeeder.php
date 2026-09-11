<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * تهيئة قاعدة البيانات والإبقاء فقط على حسابات الاختبار الرسمية الستة.
     */
    public function run(): void
    {
        $testEmails = [
            'superadmin@ihsan.com',
            'admin@ihsan.com',
            'mohammed@ihsan.com',
            'elderly@ihsan.com',
            'pending@ihsan.com',
            'suspended@ihsan.com',
            'edaod887@gmail.com',
            'edaod888@gmail.com',
            'edaod889@gmail.com',
            'edaod8810@gmail.com',
        ];

        // تعطيل قيود المفاتيح الأجنبية لتنظيف البيانات الوهمية القديمة بأمان
        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // 1. حذف كافة البيانات والطلبات والشكاوى الوهمية السابقة
        DB::table('complaints')->truncate();
        DB::table('ratings')->truncate();
        DB::table('provider_reliability_incidents')->truncate();
        DB::table('volunteer_certificates')->truncate();
        DB::table('requests')->truncate();
        DB::table('notifications')->truncate();
        DB::table('admin_audit_logs')->truncate();

        // 2. حذف كافة المستخدمين والملفات غير المنتمية لحسابات الاختبار الستة
        $nonTestUserIds = DB::table('users')->whereNotIn('email', $testEmails)->pluck('id');
        if ($nonTestUserIds->isNotEmpty()) {
            DB::table('admins')->whereIn('user_id', $nonTestUserIds)->delete();
            DB::table('service_provider_profiles')->whereIn('user_id', $nonTestUserIds)->delete();
            DB::table('elder_profiles')->whereIn('user_id', $nonTestUserIds)->delete();
            DB::table('users')->whereIn('id', $nonTestUserIds)->delete();
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // ========================================================
        // زراعة حسابات الاختبار الستة فقط
        // ========================================================

        // 1. حساب مدير النظام الأعلى (Super Admin)
        $superAdminUser = User::updateOrCreate(
            ['email' => 'superadmin@ihsan.com'],
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
            ['user_id' => $superAdminUser->id],
            ['admin_level' => 'super_admin']
        );

        // 2. حساب مدير النظام (Admin)
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@ihsan.com'],
            [
                'name' => 'مدير المنصة',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'approved',
                'rejection_reason' => null,
                'suspension_reason' => null,
                'resubmission_note' => null,
            ]
        );
        Admin::updateOrCreate(
            ['user_id' => $adminUser->id],
            ['admin_level' => 'admin']
        );

        // 3. حساب مقدم الخدمة (متطوع) - محمد أحمد
        $providerMain = User::updateOrCreate(
            ['email' => 'mohammed@ihsan.com'],
            [
                'name' => 'محمد أحمد (مقدم خدمة)',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'approved',
                'rejection_reason' => null,
                'suspension_reason' => null,
                'resubmission_note' => null,
            ]
        );
        ServiceProviderProfile::updateOrCreate(
            ['user_id' => $providerMain->id],
            [
                'full_name' => 'محمد أحمد',
                'id_number' => '401234567',
                'birth_date' => '1995-06-15',
                'phone_number' => '0599123456',
                'id_document_path' => 'documents/provider_id.png',
                'good_conduct_cert_path' => 'documents/conduct_cert.pdf',
                'tier' => 1,
                'completed_tasks_count' => 5,
                'average_rating' => 4.5,
                'is_available' => true,
                'reliability_incidents_count' => 0,
            ]
        );

        // 4. حساب كبير السن (مستفيد) - أبو أحمد التميمي
        $elderly = User::updateOrCreate(
            ['email' => 'elderly@ihsan.com'],
            [
                'name' => 'أبو أحمد التميمي (كبير سن)',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'approved',
                'rejection_reason' => null,
                'suspension_reason' => null,
                'resubmission_note' => null,
            ]
        );
        ElderProfile::updateOrCreate(
            ['user_id' => $elderly->id],
            [
                'full_name' => 'أبو أحمد التميمي',
                'id_number' => '901234567',
                'birth_date' => '1955-03-10',
                'city' => 'مدينة غزة',
                'address' => 'شارع النصر - بجوار مدرسة الهدى',
                'housing_type' => 'house',
                'phone_number' => '0599987654',
                'id_document_path' => 'documents/elderly_id.png',
            ]
        );

        // 5. حساب متطوع بانتظار الاعتماد (Pending)
        $pendingUser = User::updateOrCreate(
            ['email' => 'pending@ihsan.com'],
            [
                'name' => 'سارة العبدالله (بانتظار الاعتماد)',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'pending',
                'rejection_reason' => null,
                'suspension_reason' => null,
                'resubmission_note' => null,
            ]
        );
        ServiceProviderProfile::updateOrCreate(
            ['user_id' => $pendingUser->id],
            [
                'full_name' => 'سارة العبدالله',
                'id_number' => '409876543',
                'birth_date' => '2001-01-01',
                'phone_number' => '0599000111',
                'id_document_path' => 'documents/pending_id.png',
                'good_conduct_cert_path' => 'documents/pending_conduct.pdf',
                'tier' => 1,
                'completed_tasks_count' => 0,
                'average_rating' => 0.0,
                'is_available' => true,
                'reliability_incidents_count' => 0,
            ]
        );

        // 6. حساب موقوف (Suspended)
        User::updateOrCreate(
            ['email' => 'suspended@ihsan.com'],
            [
                'name' => 'خالد محمود (حساب موقوف)',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'suspended',
                'suspension_reason' => 'تم إيقاف الحساب بسبب تكرار عدم الحضور ومخالفة ميثاق التطوع.',
            ]
        );
    }
}
