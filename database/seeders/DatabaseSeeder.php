<?php

namespace Database\Seeders;

use App\Models\ProviderSetting;
use App\Models\RequestAttempt;
use App\Models\ServiceRequest;
use App\Models\ServiceReview;
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
        // 1. حساب مقدم الخدمة الرئيسي (محمد أحمد) كما في وثيقة ولقطات النظام
        $providerMain = User::updateOrCreate(
            ['email' => 'mohammed@ihsan.com'],
            [
                'name' => 'محمد أحمد',
                'password' => Hash::make('password'),
                'account_type' => 'volunteer',
                'email_verified_at' => now(),
            ]
        );

        $providerMain->registrationProfile()->updateOrCreate(
            ['user_id' => $providerMain->id],
            [
                'date_of_birth' => '1995-06-15',
                'phone' => '0599112233',
                'identity_number' => '402891234',
                'city' => 'مدينة غزة',
                'address' => 'حي الرمال',
            ]
        );

        // إعدادات التوفر ومؤشرات الالتزام لمحمد أحمد (مطابقة لصفحات 4 و 20 و 21)
        ProviderSetting::updateOrCreate(
            ['user_id' => $providerMain->id],
            [
                'available_days' => ['sat', 'sun', 'mon', 'tue', 'wed', 'thu'],
                'available_from' => '08:00:00',
                'available_to' => '18:00:00',
                'is_available' => true,
                'offered_services' => ['grocery', 'medical_escort', 'medicine', 'home_help'],
                'service_city' => 'مدينة غزة',
                'coverage_radius_km' => 5,
                'commitment_score' => 92,
                'punctuality_rate' => 94,
                'completion_rate' => 97,
                'response_rate' => 88,
            ]
        );

        // 2. حساب كبير السن الرئيسي (أبو أحمد التميمي)
        $elderly = User::updateOrCreate(
            ['email' => 'elderly@ihsan.com'],
            [
                'name' => 'الحاج أبو أحمد التميمي',
                'password' => Hash::make('password'),
                'account_type' => 'elderly',
                'email_verified_at' => now(),
            ]
        );

        $elderly->registrationProfile()->updateOrCreate(
            ['user_id' => $elderly->id],
            [
                'date_of_birth' => '1952-04-15',
                'phone' => '0599123456',
                'city' => 'مدينة غزة',
                'address' => 'حي الرمال - شارع الوحدة بجوار صيدلية الشفاء',
                'housing_type' => 'apartment',
                'extra_info' => 'أعاني من صعوبة طفيفة في الحركة وأحتاج مساعدة دورية في إحضار الأدوية والتسوق.',
            ]
        );

        // كبير سن إضافي
        $elderly2 = User::updateOrCreate(
            ['email' => 'elderly2@ihsan.com'],
            [
                'name' => 'الحاجة أم إبراهيم النجار',
                'password' => Hash::make('password'),
                'account_type' => 'elderly',
                'email_verified_at' => now(),
            ]
        );

        $elderly2->registrationProfile()->updateOrCreate(
            ['user_id' => $elderly2->id],
            [
                'date_of_birth' => '1950-09-20',
                'phone' => '0599654321',
                'city' => 'مدينة غزة',
                'address' => 'حي النصر - بالقرب من العيادة الصحية',
                'housing_type' => 'house',
            ]
        );

        // 3. طلبات متاحة بانتظار مقدمي خدمة (صفحة 6 بالملف)
        $avail1 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1050'],
            [
                'user_id' => $elderly->id,
                'title' => 'شراء أغراض منزلية',
                'service_type' => 'grocery',
                'description' => 'شراء أدوية وبعض الاحتياجات من متجر قريب وتسليم الفاتورة.',
                'location' => 'حي الرمال - شارع الوحدة',
                'district' => 'حي الرمال',
                'distance_km' => 1.8,
                'scheduled_at' => Carbon::today()->setTime(16, 30),
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'attempts_count' => 1,
            ]
        );
        $avail1->attempts()->updateOrCreate(['attempt_number' => 1], [
            'scheduled_at' => $avail1->scheduled_at,
            'location' => $avail1->location,
            'outcome' => 'pending',
            'notes' => 'المحاولة الأولى لنشر الطلب',
        ]);

        $avail2 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1051'],
            [
                'user_id' => $elderly2->id,
                'title' => 'مرافقة إلى موعد طبي',
                'service_type' => 'medical_escort',
                'description' => 'مرافقة المستفيد إلى عيادة قريبة والعودة إلى المنزل بعد الفحص.',
                'location' => 'حي النصر - العيادة المركزية',
                'district' => 'حي النصر',
                'distance_km' => 2.4,
                'scheduled_at' => Carbon::tomorrow()->setTime(9, 0),
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'attempts_count' => 1,
            ]
        );
        $avail2->attempts()->updateOrCreate(['attempt_number' => 1], [
            'scheduled_at' => $avail2->scheduled_at,
            'location' => $avail2->location,
            'outcome' => 'pending',
            'notes' => 'المحاولة الأولى لنشر الطلب',
        ]);

        $avail3 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1052'],
            [
                'user_id' => $elderly->id,
                'title' => 'إحضار دواء',
                'service_type' => 'medicine',
                'description' => 'إحضار وصفة طبية شهرية من الصيدلية المركزية.',
                'location' => 'حي الرمال - صيدلية الشفاء',
                'district' => 'حي الرمال',
                'distance_km' => 3.1,
                'scheduled_at' => Carbon::tomorrow()->setTime(14, 0),
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'attempts_count' => 1,
            ]
        );

        $avail4 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1053'],
            [
                'user_id' => $elderly2->id,
                'title' => 'مساعدة منزلية خفيفة',
                'service_type' => 'home_help',
                'description' => 'المساعدة في إعادة ترتيب بعض الصناديق والاحتياجات المنزلية الخفيفة.',
                'location' => 'حي النصر',
                'district' => 'حي النصر',
                'distance_km' => 4.0,
                'scheduled_at' => Carbon::today()->addDays(2)->setTime(11, 0),
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'attempts_count' => 1,
            ]
        );

        // 4. مهام قادمة وجارية مسندة لمحمد أحمد (مطابقة للصور 8، 10، 13، 14)
        
        // مهمة 1: تم القبول (طلب رقم #1048 - شراء أغراض منزلية)
        $taskAccepted = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1048'],
            [
                'user_id' => $elderly->id,
                'assigned_provider_id' => $providerMain->id,
                'title' => 'شراء أغراض منزلية',
                'service_type' => 'grocery',
                'description' => 'مساعدة في شراء خضراوات وفواكه وبعض المعلبات من المتجر القريب.',
                'location' => 'حي الرمال - شارع الوحدة - عمارة الأمل ط3',
                'district' => 'حي الرمال',
                'distance_km' => 1.8,
                'scheduled_at' => Carbon::now()->addMinutes(45),
                'status' => ServiceRequest::STATUS_ACCEPTED,
                'accepted_at' => Carbon::now()->subMinutes(15),
                'attempts_count' => 1,
            ]
        );
        $taskAccepted->attempts()->updateOrCreate(['attempt_number' => 1], [
            'provider_id' => $providerMain->id,
            'scheduled_at' => $taskAccepted->scheduled_at,
            'location' => $taskAccepted->location,
            'outcome' => 'accepted',
            'notes' => 'تم قبول الطلب وإسناده لمحمد أحمد',
        ]);

        // مهمة 2: قيد التنفيذ (طلب رقم #1041 - مرافقة إلى موعد طبي)
        $taskInProgress = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1041'],
            [
                'user_id' => $elderly2->id,
                'assigned_provider_id' => $providerMain->id,
                'title' => 'مرافقة إلى موعد طبي',
                'service_type' => 'medical_escort',
                'description' => 'مرافقة الحاجة أم إبراهيم إلى عيادة العيون والانتظار معها حتى انتهاء الفحص.',
                'location' => 'حي النصر - العيادة التخصصية',
                'district' => 'حي النصر',
                'distance_km' => 2.4,
                'scheduled_at' => Carbon::now()->subMinutes(30),
                'status' => ServiceRequest::STATUS_IN_PROGRESS,
                'accepted_at' => Carbon::now()->subHours(2),
                'on_the_way_at' => Carbon::now()->subMinutes(45),
                'arrived_at' => Carbon::now()->subMinutes(30),
                'started_at' => Carbon::now()->subMinutes(25),
                'attempts_count' => 1,
            ]
        );
        $taskInProgress->attempts()->updateOrCreate(['attempt_number' => 1], [
            'provider_id' => $providerMain->id,
            'scheduled_at' => $taskInProgress->scheduled_at,
            'location' => $taskInProgress->location,
            'outcome' => 'in_progress',
            'notes' => 'الخدمة جارية الآن مع الحاجة أم إبراهيم',
        ]);

        // مهمة 3: بانتظار التأكيد (طلب رقم #1038 - إحضار دواء)
        $taskPendingConfirm = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1038'],
            [
                'user_id' => $elderly->id,
                'assigned_provider_id' => $providerMain->id,
                'title' => 'إحضار دواء',
                'service_type' => 'medicine',
                'description' => 'إحضار أدوية السكري والضغط وتسليم الفاتورة للحاج.',
                'location' => 'حي الرمال - شارع الوحدة',
                'district' => 'حي الرمال',
                'distance_km' => 1.5,
                'scheduled_at' => Carbon::now()->subHours(2),
                'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
                'accepted_at' => Carbon::now()->subHours(3),
                'started_at' => Carbon::now()->subHours(2),
                'completion_notes' => 'تم شراء كافة الأدوية الموصوفة وتسليم الإيصال للحاج أبو أحمد.',
                'attempts_count' => 1,
            ]
        );
        $taskPendingConfirm->attempts()->updateOrCreate(['attempt_number' => 1], [
            'provider_id' => $providerMain->id,
            'scheduled_at' => $taskPendingConfirm->scheduled_at,
            'location' => $taskPendingConfirm->location,
            'outcome' => 'pending_confirmation',
            'notes' => 'أنهى محمد أحمد المهمة وبانتظار تأكيد المستفيد',
        ]);

        // 5. مهام مكتملة مع تقييمات لمحمد أحمد (صفحة 20)
        $completed1 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1030'],
            [
                'user_id' => $elderly->id,
                'assigned_provider_id' => $providerMain->id,
                'title' => 'مرافقة إلى موعد طبي',
                'service_type' => 'medical_escort',
                'description' => 'مرافقة المستفيد إلى عيادة الأسنان والعودة.',
                'location' => 'حي الرمال',
                'district' => 'حي الرمال',
                'distance_km' => 2.0,
                'scheduled_at' => Carbon::now()->subDays(2),
                'status' => ServiceRequest::STATUS_COMPLETED,
                'accepted_at' => Carbon::now()->subDays(2)->subHours(2),
                'started_at' => Carbon::now()->subDays(2),
                'completed_at' => Carbon::now()->subDays(2)->addHour(),
                'completion_notes' => 'تمت المرافقة بنجاح وعودة الحاج إلى منزله بسلام.',
                'attempts_count' => 1,
            ]
        );
        ServiceReview::updateOrCreate(
            ['service_request_id' => $completed1->id],
            [
                'elderly_id' => $elderly->id,
                'provider_id' => $providerMain->id,
                'rating' => 5,
                'comment' => 'شاب خلوق ومحترم جداً، وصل قبل الموعد وساعدني بكل أدب وصبر. جزاه الله خيراً.',
            ]
        );

        $completed2 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1025'],
            [
                'user_id' => $elderly2->id,
                'assigned_provider_id' => $providerMain->id,
                'title' => 'شراء أغراض منزلية',
                'service_type' => 'grocery',
                'description' => 'إحضار مواد غذائية ومستلزمات منزلية.',
                'location' => 'حي النصر',
                'district' => 'حي النصر',
                'distance_km' => 2.5,
                'scheduled_at' => Carbon::now()->subDays(4),
                'status' => ServiceRequest::STATUS_COMPLETED,
                'accepted_at' => Carbon::now()->subDays(4)->subHours(3),
                'started_at' => Carbon::now()->subDays(4),
                'completed_at' => Carbon::now()->subDays(4)->addHours(1),
                'completion_notes' => 'تم تسليم كافة الأغراض بدقة.',
                'attempts_count' => 1,
            ]
        );
        ServiceReview::updateOrCreate(
            ['service_request_id' => $completed2->id],
            [
                'elderly_id' => $elderly2->id,
                'provider_id' => $providerMain->id,
                'rating' => 5,
                'comment' => 'خدمة ممتازة وسريعة، أمين جداً وأحضر كل شيء بدقة.',
            ]
        );

        $completed3 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1018'],
            [
                'user_id' => $elderly->id,
                'assigned_provider_id' => $providerMain->id,
                'title' => 'إحضار دواء',
                'service_type' => 'medicine',
                'description' => 'إحضار أدوية من الصيدلية.',
                'location' => 'حي الرمال',
                'district' => 'حي الرمال',
                'distance_km' => 1.2,
                'scheduled_at' => Carbon::now()->subDays(7),
                'status' => ServiceRequest::STATUS_COMPLETED,
                'accepted_at' => Carbon::now()->subDays(7)->subHours(2),
                'started_at' => Carbon::now()->subDays(7),
                'completed_at' => Carbon::now()->subDays(7)->addMinutes(45),
                'attempts_count' => 1,
            ]
        );
        ServiceReview::updateOrCreate(
            ['service_request_id' => $completed3->id],
            [
                'elderly_id' => $elderly->id,
                'provider_id' => $providerMain->id,
                'rating' => 4,
                'comment' => 'بارك الله فيك، خدمة طيبة وتعامل ممتاز.',
            ]
        );
    }
}
