<?php

namespace Database\Seeders;

use App\Models\ElderProfile;
use App\Models\Rating;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. حساب مقدم الخدمة الرئيسي (محمد أحمد)
        $providerMain = User::updateOrCreate(
            ['email' => 'mohammed@ihsan.com'],
            [
                'name' => 'محمد أحمد',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'approved',
            ]
        );

        $providerProfile = ServiceProviderProfile::updateOrCreate(
            ['user_id' => $providerMain->id],
            [
                'full_name' => 'محمد أحمد',
                'birth_date' => '1995-06-15',
                'phone_number' => '0599123456',
                'id_document_path' => 'documents/provider_id.png',
                'good_conduct_cert_path' => 'documents/conduct_cert.pdf',
                'tier' => 1,
                'completed_tasks_count' => 3,
                'average_rating' => 4.7,
                'is_available' => true,
                'reliability_incidents_count' => 0,
            ]
        );

        // 2. حساب كبير السن الرئيسي (أبو أحمد التميمي)
        $elderly = User::updateOrCreate(
            ['email' => 'elderly@ihsan.com'],
            [
                'name' => 'الحاج أبو أحمد التميمي',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'approved',
            ]
        );

        $elderlyProfile = ElderProfile::updateOrCreate(
            ['user_id' => $elderly->id],
            [
                'full_name' => 'أبو أحمد التميمي',
                'city' => 'مدينة غزة',
                'phone_number' => '0599987654',
                'id_document_path' => 'documents/elderly_id.png',
            ]
        );

        // كبير سن إضافي
        $elderly2 = User::updateOrCreate(
            ['email' => 'elderly2@ihsan.com'],
            [
                'name' => 'الحاجة أم إبراهيم النجار',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'status' => 'approved',
            ]
        );

        $elderly2Profile = ElderProfile::updateOrCreate(
            ['user_id' => $elderly2->id],
            [
                'full_name' => 'أم إبراهيم النجار',
                'city' => 'مدينة غزة',
                'phone_number' => '0599555444',
                'id_document_path' => null,
            ]
        );

        // 3. طلبات متاحة بانتظار مقدمي خدمة
        $avail1 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1050'],
            [
                'elder_id' => $elderlyProfile->id,
                'title' => 'شراء أغراض منزلية',
                'service_type' => 'grocery',
                'pricing_type' => 'volunteer',
                'timing_type' => 'scheduled',
                'gender_preference' => 'any',
                'description' => 'شراء أدوية وبعض الاحتياجات من متجر قريب وتسليم الفاتورة.',
                'location' => 'حي الرمال - شارع الوحدة',
                'scheduled_at' => Carbon::today()->setTime(16, 30),
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
            ]
        );

        $avail2 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1051'],
            [
                'elder_id' => $elderly2Profile->id,
                'title' => 'مرافقة إلى موعد طبي',
                'service_type' => 'medical_escort',
                'pricing_type' => 'volunteer',
                'timing_type' => 'scheduled',
                'gender_preference' => 'any',
                'description' => 'مرافقة المستفيد إلى عيادة قريبة والعودة إلى المنزل بعد الفحص.',
                'location' => 'حي النصر - العيادة المركزية',
                'scheduled_at' => Carbon::tomorrow()->setTime(9, 0),
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
            ]
        );

        $avail3 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1052'],
            [
                'elder_id' => $elderlyProfile->id,
                'title' => 'إحضار دواء',
                'service_type' => 'medicine',
                'pricing_type' => 'volunteer',
                'timing_type' => 'scheduled',
                'gender_preference' => 'any',
                'description' => 'إحضار وصفة طبية شهرية من الصيدلية المركزية.',
                'location' => 'حي الرمال - صيدلية الشفاء',
                'scheduled_at' => Carbon::tomorrow()->setTime(14, 0),
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
            ]
        );

        $avail4 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1053'],
            [
                'elder_id' => $elderly2Profile->id,
                'title' => 'مساعدة منزلية خفيفة',
                'service_type' => 'home_help',
                'pricing_type' => 'volunteer',
                'timing_type' => 'scheduled',
                'gender_preference' => 'any',
                'description' => 'المساعدة في إعادة ترتيب بعض الصناديق والاحتياجات المنزلية الخفيفة.',
                'location' => 'حي النصر',
                'scheduled_at' => Carbon::today()->addDays(2)->setTime(11, 0),
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
            ]
        );

        // 4. مهام مسندة لمحمد أحمد
        
        // مهمة 1: تم القبول
        $taskAccepted = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1048'],
            [
                'elder_id' => $elderlyProfile->id,
                'provider_id' => $providerProfile->id,
                'title' => 'شراء أغراض منزلية',
                'service_type' => 'grocery',
                'pricing_type' => 'volunteer',
                'timing_type' => 'scheduled',
                'gender_preference' => 'any',
                'description' => 'مساعدة في شراء خضراوات وفواكه وبعض المعلبات من المتجر القريب.',
                'location' => 'حي الرمال - شارع الوحدة - عمارة الأمل ط3',
                'scheduled_at' => Carbon::now()->addMinutes(45),
                'status' => ServiceRequest::STATUS_ACCEPTED,
                'accepted_at' => Carbon::now()->subMinutes(15),
                'assigned_at' => Carbon::now()->subMinutes(15),
            ]
        );

        // مهمة 2: قيد التنفيذ
        $taskInProgress = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1041'],
            [
                'elder_id' => $elderly2Profile->id,
                'provider_id' => $providerProfile->id,
                'title' => 'مرافقة إلى موعد طبي',
                'service_type' => 'medical_escort',
                'pricing_type' => 'volunteer',
                'timing_type' => 'scheduled',
                'gender_preference' => 'any',
                'description' => 'مرافقة الحاجة أم إبراهيم إلى عيادة العيون والانتظار معها حتى انتهاء الفحص.',
                'location' => 'حي النصر - العيادة التخصصية',
                'scheduled_at' => Carbon::now()->subMinutes(30),
                'status' => ServiceRequest::STATUS_IN_PROGRESS,
                'accepted_at' => Carbon::now()->subHours(2),
                'assigned_at' => Carbon::now()->subHours(2),
                'started_at' => Carbon::now()->subMinutes(25),
            ]
        );

        // مهمة 3: بانتظار التأكيد
        $taskPendingConfirm = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1038'],
            [
                'elder_id' => $elderlyProfile->id,
                'provider_id' => $providerProfile->id,
                'title' => 'إحضار دواء',
                'service_type' => 'medicine',
                'pricing_type' => 'volunteer',
                'timing_type' => 'scheduled',
                'gender_preference' => 'any',
                'description' => 'إحضار أدوية السكري والضغط وتسليم الفاتورة للحاج.',
                'location' => 'حي الرمال - شارع الوحدة',
                'scheduled_at' => Carbon::now()->subHours(2),
                'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
                'accepted_at' => Carbon::now()->subHours(3),
                'assigned_at' => Carbon::now()->subHours(3),
                'started_at' => Carbon::now()->subHours(2),
            ]
        );

        // 5. مهام مكتملة مع تقييمات لمحمد أحمد
        $completed1 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1030'],
            [
                'elder_id' => $elderlyProfile->id,
                'provider_id' => $providerProfile->id,
                'title' => 'مرافقة إلى موعد طبي',
                'service_type' => 'medical_escort',
                'pricing_type' => 'volunteer',
                'timing_type' => 'scheduled',
                'gender_preference' => 'any',
                'description' => 'مرافقة المستفيد إلى عيادة الأسنان والعودة.',
                'location' => 'حي الرمال',
                'scheduled_at' => Carbon::now()->subDays(2),
                'status' => ServiceRequest::STATUS_COMPLETED,
                'accepted_at' => Carbon::now()->subDays(2)->subHours(2),
                'assigned_at' => Carbon::now()->subDays(2)->subHours(2),
                'started_at' => Carbon::now()->subDays(2),
                'completed_at' => Carbon::now()->subDays(2)->addHour(),
            ]
        );
        Rating::updateOrCreate(
            ['service_request_id' => $completed1->id, 'rater_role' => 'elder'],
            [
                'elderly_id' => $elderly->id,
                'provider_id' => $providerMain->id,
                'stars' => 5,
                'comment' => 'شاب خلوق ومحترم جداً، وصل قبل الموعد وساعدني بكل أدب وصبر. جزاه الله خيراً.',
                'visible_to_provider' => true,
            ]
        );

        $completed2 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1025'],
            [
                'elder_id' => $elderly2Profile->id,
                'provider_id' => $providerProfile->id,
                'title' => 'شراء أغراض منزلية',
                'service_type' => 'grocery',
                'pricing_type' => 'volunteer',
                'timing_type' => 'scheduled',
                'gender_preference' => 'any',
                'description' => 'إحضار مواد غذائية ومستلزمات منزلية.',
                'location' => 'حي النصر',
                'scheduled_at' => Carbon::now()->subDays(4),
                'status' => ServiceRequest::STATUS_COMPLETED,
                'accepted_at' => Carbon::now()->subDays(4)->subHours(3),
                'assigned_at' => Carbon::now()->subDays(4)->subHours(3),
                'started_at' => Carbon::now()->subDays(4),
                'completed_at' => Carbon::now()->subDays(4)->addHours(1),
            ]
        );
        Rating::updateOrCreate(
            ['service_request_id' => $completed2->id, 'rater_role' => 'elder'],
            [
                'elderly_id' => $elderly2->id,
                'provider_id' => $providerMain->id,
                'stars' => 5,
                'comment' => 'خدمة ممتازة وسريعة، أمين جداً وأحضر كل شيء بدقة.',
                'visible_to_provider' => true,
            ]
        );

        $completed3 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1018'],
            [
                'elder_id' => $elderlyProfile->id,
                'provider_id' => $providerProfile->id,
                'title' => 'إحضار دواء',
                'service_type' => 'medicine',
                'pricing_type' => 'volunteer',
                'timing_type' => 'scheduled',
                'gender_preference' => 'any',
                'description' => 'إحضار أدوية من الصيدلية.',
                'location' => 'حي الرمال',
                'scheduled_at' => Carbon::now()->subDays(7),
                'status' => ServiceRequest::STATUS_COMPLETED,
                'accepted_at' => Carbon::now()->subDays(7)->subHours(2),
                'assigned_at' => Carbon::now()->subDays(7)->subHours(2),
                'started_at' => Carbon::now()->subDays(7),
                'completed_at' => Carbon::now()->subDays(7)->addMinutes(45),
            ]
        );
        Rating::updateOrCreate(
            ['service_request_id' => $completed3->id, 'rater_role' => 'elder'],
            [
                'elderly_id' => $elderly->id,
                'provider_id' => $providerMain->id,
                'stars' => 4,
                'comment' => 'بارك الله فيك، خدمة طيبة وتعامل ممتاز.',
                'visible_to_provider' => true,
            ]
        );
    }
}
