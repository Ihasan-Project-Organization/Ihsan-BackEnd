<?php

namespace Database\Seeders;

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
        // 1. حساب كبير سن رئيسي للتجربة
        $elderly = User::firstOrCreate(
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
                'city' => 'غزة',
                'address' => 'حي الرمال - شارع الوحدة بجوار صيدلية الشفاء',
                'housing_type' => 'apartment',
                'extra_info' => 'أعاني من صعوبة طفيفة في الحركة وأحتاج مساعدة دورية في إحضار الأدوية.',
            ]
        );

        // 2. حسابات متطوعين للتجربة
        $volunteer1 = User::firstOrCreate(
            ['email' => 'volunteer1@ihsan.com'],
            [
                'name' => 'ليان محمود',
                'password' => Hash::make('password'),
                'account_type' => 'volunteer',
                'email_verified_at' => now(),
            ]
        );

        $volunteer1->registrationProfile()->updateOrCreate(
            ['user_id' => $volunteer1->id],
            [
                'date_of_birth' => '1998-07-20',
                'phone' => '0599887766',
                'identity_number' => '402198765',
                'city' => 'غزة',
                'address' => 'حي النصر',
            ]
        );

        $volunteer2 = User::firstOrCreate(
            ['email' => 'volunteer2@ihsan.com'],
            [
                'name' => 'يوسف خالد',
                'password' => Hash::make('password'),
                'account_type' => 'volunteer',
                'email_verified_at' => now(),
            ]
        );

        $volunteer2->registrationProfile()->updateOrCreate(
            ['user_id' => $volunteer2->id],
            [
                'date_of_birth' => '1996-03-12',
                'phone' => '0599112233',
                'identity_number' => '401987654',
                'city' => 'غزة',
                'address' => 'حي تل الهوى',
            ]
        );

        $volunteer3 = User::firstOrCreate(
            ['email' => 'volunteer3@ihsan.com'],
            [
                'name' => 'سامر علي',
                'password' => Hash::make('password'),
                'account_type' => 'volunteer',
                'email_verified_at' => now(),
            ]
        );

        $volunteer3->registrationProfile()->updateOrCreate(
            ['user_id' => $volunteer3->id],
            [
                'date_of_birth' => '1995-11-05',
                'phone' => '0599334455',
                'identity_number' => '403456789',
                'city' => 'غزة',
                'address' => 'حي الدرج',
            ]
        );

        // 3. إدخال طلبات تغطي كافة التبويبات والمسارات الـ 5 المذكورة في ملفات النظام

        // -------------------------------------------------------------
        // مسار 1: بانتظار قبول مقدم خدمة (تبويب: النشطة)
        // -------------------------------------------------------------
        $req1 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1045'],
            [
                'user_id' => $elderly->id,
                'title' => 'شراء أغراض واحتياجات منزلية',
                'description' => 'شراء بعض الاحتياجات المنزلية الأساسية من السوبرماركت (خضار وحليب وخبز).',
                'location' => 'حي النصر - بالقرب من مسجد النصر',
                'scheduled_at' => Carbon::now()->addDays(2)->setHour(11)->setMinute(0),
                'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'attempts_count' => 1,
            ]
        );
        $req1->attempts()->updateOrCreate(['attempt_number' => 1], [
            'scheduled_at' => $req1->scheduled_at,
            'location' => $req1->location,
            'outcome' => 'pending',
            'notes' => 'المحاولة الأولى لإنشاء ونشر الطلب',
        ]);

        // -------------------------------------------------------------
        // مسار 2: تم قبول الطلب (تبويب: النشطة)
        // -------------------------------------------------------------
        $req2 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1044'],
            [
                'user_id' => $elderly->id,
                'assigned_provider_id' => $volunteer1->id,
                'title' => 'زيارة اجتماعية وقراءة بعض الرسائل',
                'description' => 'زيارة ودية ومساعدة في قراءة بعض الخطابات العائلية والجريدة.',
                'location' => 'حي الرمال - شارع الوحدة',
                'scheduled_at' => Carbon::now()->addDay()->setHour(17)->setMinute(0),
                'status' => ServiceRequest::STATUS_ACCEPTED,
                'attempts_count' => 1,
                'accepted_at' => Carbon::now()->subHours(2),
            ]
        );
        $req2->attempts()->updateOrCreate(['attempt_number' => 1], [
            'provider_id' => $volunteer1->id,
            'scheduled_at' => $req2->scheduled_at,
            'location' => $req2->location,
            'outcome' => 'pending',
            'notes' => 'تم قبول الطلب من قبل المتطوعة ليان محمود',
        ]);

        // -------------------------------------------------------------
        // مسار 3: قيد التنفيذ (تبويب: النشطة)
        // -------------------------------------------------------------
        $req3 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1040'],
            [
                'user_id' => $elderly->id,
                'assigned_provider_id' => $volunteer2->id,
                'title' => 'مرافقة للمستشفى للعيادة الخارجية',
                'description' => 'المساعدة في الوصول إلى مجمع الشفاء الطبي والعودة إلى المنزل بعد مراجعة الطبيب.',
                'location' => 'حي الرمال - شارع الوحدة إلى مجمع الشفاء',
                'scheduled_at' => Carbon::now()->setHour(9)->setMinute(15),
                'status' => ServiceRequest::STATUS_IN_PROGRESS,
                'attempts_count' => 1,
                'accepted_at' => Carbon::now()->subHours(3),
                'started_at' => Carbon::now()->subMinutes(20),
            ]
        );
        $req3->attempts()->updateOrCreate(['attempt_number' => 1], [
            'provider_id' => $volunteer2->id,
            'scheduled_at' => $req3->scheduled_at,
            'location' => $req3->location,
            'outcome' => 'in_progress',
            'notes' => 'بدأ المتطوع يوسف خالد في تنفيذ المهمة والمرافقة',
        ]);

        // -------------------------------------------------------------
        // مسار 4: بانتظار تأكيد كبير السن (تبويب: النشطة)
        // -------------------------------------------------------------
        $req4 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1034'],
            [
                'user_id' => $elderly->id,
                'assigned_provider_id' => $volunteer3->id,
                'title' => 'مساعدة منزلية في ترتيب الاحتياجات',
                'description' => 'أنهى مقدم الخدمة المهمة ومساعدة الحاج في ترتيب الأغراض المنزلية الثقيلة.',
                'location' => 'حي الدرج',
                'scheduled_at' => Carbon::now()->subHour(),
                'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
                'attempts_count' => 1,
                'accepted_at' => Carbon::now()->subHours(4),
                'started_at' => Carbon::now()->subHour(),
            ]
        );
        $req4->attempts()->updateOrCreate(['attempt_number' => 1], [
            'provider_id' => $volunteer3->id,
            'scheduled_at' => $req4->scheduled_at,
            'location' => $req4->location,
            'outcome' => 'pending_confirmation',
            'notes' => 'المتطوع سامر علي أتم المساعدة وبانتظار تأكيد الحاج',
        ]);

        // -------------------------------------------------------------
        // مسار 5: لم يتم العثور على مقدم خدمة (تبويب: بحاجة إلى إجراء)
        // -------------------------------------------------------------
        $req5 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1042'],
            [
                'user_id' => $elderly->id,
                'title' => 'شراء دواء الضغط من صيدلية الشفاء',
                'description' => 'إحضار دواء الضغط والسكري من صيدلية الشفاء مع إحضار الفاتورة.',
                'location' => 'حي الرمال - شارع الوحدة',
                'scheduled_at' => Carbon::now()->subHours(5),
                'status' => ServiceRequest::STATUS_NO_PROVIDER_FOUND,
                'attempts_count' => 1,
            ]
        );
        $req5->attempts()->updateOrCreate(['attempt_number' => 1], [
            'scheduled_at' => $req5->scheduled_at,
            'location' => $req5->location,
            'outcome' => 'no_provider',
            'notes' => 'انتهى موعد التنفيذ دون قبول من أي متطوع',
        ]);

        // -------------------------------------------------------------
        // مسار 6: اعتذر مقدم الخدمة (تبويب: بحاجة إلى إجراء)
        // -------------------------------------------------------------
        $req6 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1036'],
            [
                'user_id' => $elderly->id,
                'title' => 'مساعدة بسيطة في ترتيب الاحتياجات',
                'description' => 'اعتذر مقدم الخدمة عمر ناصر لظرف طارئ بعد القبول.',
                'location' => 'حي النصر',
                'scheduled_at' => Carbon::now()->subDay(),
                'status' => ServiceRequest::STATUS_PROVIDER_APOLOGIZED,
                'attempts_count' => 1,
            ]
        );
        $req6->attempts()->updateOrCreate(['attempt_number' => 1], [
            'provider_id' => $volunteer2->id,
            'scheduled_at' => $req6->scheduled_at,
            'location' => $req6->location,
            'outcome' => 'apologized',
            'notes' => 'اعتذر المتطوع بسبب ظرف صحي طارئ',
        ]);

        // -------------------------------------------------------------
        // مسار 7: مقدم الخدمة متأخر (تبويب: بحاجة إلى إجراء)
        // -------------------------------------------------------------
        $req7 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1039'],
            [
                'user_id' => $elderly->id,
                'assigned_provider_id' => $volunteer3->id,
                'title' => 'مرافقة إلى العيادة الخارجية',
                'description' => 'مرافقة لموعد في العيادة القريبة، انتهت مهلة الوصول المحددة دون بدء.',
                'location' => 'حي الرمال',
                'scheduled_at' => Carbon::now()->subMinutes(45),
                'status' => ServiceRequest::STATUS_PROVIDER_DELAYED,
                'attempts_count' => 1,
                'accepted_at' => Carbon::now()->subHours(2),
            ]
        );
        $req7->attempts()->updateOrCreate(['attempt_number' => 1], [
            'provider_id' => $volunteer3->id,
            'scheduled_at' => $req7->scheduled_at,
            'location' => $req7->location,
            'outcome' => 'delayed',
            'notes' => 'تأخر المتطوع عن موعد الوصول المحدد',
        ]);

        // -------------------------------------------------------------
        // مسار 8: تم تنفيذ الطلب مع تقييم (تبويب: المكتملة)
        // -------------------------------------------------------------
        $req8 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1028'],
            [
                'user_id' => $elderly->id,
                'assigned_provider_id' => $volunteer1->id,
                'title' => 'طلب دعم ومراجعة بعض الأوراق',
                'description' => 'مساعدة في مراجعة وتنسيق بعض المعاملات والأوراق الرسمية.',
                'location' => 'حي الرمال',
                'scheduled_at' => Carbon::now()->subDays(3),
                'status' => ServiceRequest::STATUS_COMPLETED,
                'attempts_count' => 1,
                'accepted_at' => Carbon::now()->subDays(3)->subHours(2),
                'started_at' => Carbon::now()->subDays(3),
                'completed_at' => Carbon::now()->subDays(3)->addHours(1),
            ]
        );
        $req8->attempts()->updateOrCreate(['attempt_number' => 1], [
            'provider_id' => $volunteer1->id,
            'scheduled_at' => $req8->scheduled_at,
            'location' => $req8->location,
            'outcome' => 'completed',
            'notes' => 'تم إنجاز المهمة بنجاح تام',
        ]);
        ServiceReview::updateOrCreate(
            ['service_request_id' => $req8->id],
            [
                'elderly_id' => $elderly->id,
                'provider_id' => $volunteer1->id,
                'rating' => 5,
                'comment' => 'متطوعة ممتازة ومهذبة جداً وساعدتني بكل أمانة وإخلاص، جزاها الله كل خير.',
            ]
        );

        // -------------------------------------------------------------
        // مسار 9: تم إلغاء الطلب (تبويب: ملفاة / ملغاة)
        // -------------------------------------------------------------
        $req9 = ServiceRequest::updateOrCreate(
            ['public_id' => '#REQ-1021'],
            [
                'user_id' => $elderly->id,
                'title' => 'شراء أغراض منزلية خفيفة',
                'description' => 'طلب سابق تم إلغاؤه قبل أن يبدأ تنفيذه.',
                'location' => 'حي الرمال - شارع الوحدة',
                'scheduled_at' => Carbon::now()->subDays(5),
                'status' => ServiceRequest::STATUS_CANCELLED,
                'attempts_count' => 1,
                'cancelled_at' => Carbon::now()->subDays(5),
                'cancellation_reason' => 'تمت المساعدة من أحد الأقارب ولم تعد الخدمة مطلوبة',
            ]
        );
        $req9->attempts()->updateOrCreate(['attempt_number' => 1], [
            'scheduled_at' => $req9->scheduled_at,
            'location' => $req9->location,
            'outcome' => 'cancelled',
            'notes' => 'ألغي الطلب من قبل كبير السن',
        ]);
    }
}
