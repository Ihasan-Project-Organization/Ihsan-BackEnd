<?php

namespace Database\Seeders;

use App\Models\ElderProfile;
use App\Models\Notification;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use Illuminate\Database\Seeder;

class DemoServiceRequestsSeeder extends Seeder
{
    /**
     * زراعة طلبات خدمة تجريبية تغطي جميع الحالات التشغيلية الـ 11.
     * آمنة للتشغيل المتكرر (Idempotent).
     */
    public function run(): void
    {
        $elderProfile = ElderProfile::whereHas('user', fn($q) => $q->where('email', 'elderly@ihsan.com'))->first();
        $providerProfile = ServiceProviderProfile::whereHas('user', fn($q) => $q->where('email', 'mohammed@ihsan.com'))->first();

        if (!$elderProfile) {
            $this->command->warn('⚠️ لا يوجد حساب كبير سن (elderly@ihsan.com). شغّل DatabaseSeeder أولاً.');
            return;
        }

        $requests = [
            // ═══════ 1. طلب بانتظار القبول (pending_acceptance) ═══════
            [
                'public_id'    => '#REQ-2001',
                'title'        => 'شراء أغراض منزلية',
                'service_type' => 'grocery',
                'description'  => 'أحتاج شراء خضروات وفواكه ومواد تنظيف من السوبرماركت القريب.',
                'location'     => 'حي الرمال - مدينة غزة',
                'status'       => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
                'scheduled_at' => now()->addDays(2)->setHour(10)->setMinute(0),
                'provider_id'  => null,
            ],

            // ═══════ 2. طلب تم قبوله (accepted) ═══════
            [
                'public_id'    => '#REQ-2002',
                'title'        => 'مرافقة إلى موعد طبي',
                'service_type' => 'medical_escort',
                'description'  => 'مرافقتي إلى عيادة العيون لموعد فحص دوري يوم الأحد.',
                'location'     => 'مستشفى الشفاء - غزة',
                'status'       => ServiceRequest::STATUS_ACCEPTED,
                'scheduled_at' => now()->addDays(3)->setHour(9)->setMinute(30),
                'provider_id'  => $providerProfile?->id,
                'accepted_at'  => now()->subHours(2),
            ],

            // ═══════ 3. طلب تم توكيله رسمياً (assigned) ═══════
            [
                'public_id'    => '#REQ-2003',
                'title'        => 'إحضار أدوية من الصيدلية',
                'service_type' => 'medicine',
                'description'  => 'إحضار أدوية الضغط والسكري من صيدلية الشفاء حسب الوصفة المرفقة.',
                'location'     => 'صيدلية الشفاء - شارع الوحدة',
                'status'       => ServiceRequest::STATUS_ASSIGNED,
                'scheduled_at' => now()->addDay()->setHour(14)->setMinute(0),
                'provider_id'  => $providerProfile?->id,
                'accepted_at'  => now()->subDay(),
                'assigned_at'  => now()->subHours(6),
            ],

            // ═══════ 4. طلب قيد التنفيذ (in_progress) ═══════
            [
                'public_id'    => '#REQ-2004',
                'title'        => 'مساعدة في ترتيب المنزل',
                'service_type' => 'home_help',
                'description'  => 'مساعدة بسيطة في ترتيب وتنظيف غرفة المعيشة والمطبخ.',
                'location'     => 'شارع النصر - بجوار مدرسة الهدى',
                'status'       => ServiceRequest::STATUS_IN_PROGRESS,
                'scheduled_at' => now()->subHours(1),
                'provider_id'  => $providerProfile?->id,
                'accepted_at'  => now()->subDay(),
                'assigned_at'  => now()->subHours(5),
                'started_at'   => now()->subMinutes(45),
            ],

            // ═══════ 5. طلب بانتظار تأكيد المستفيد (pending_confirmation) ═══════
            [
                'public_id'    => '#REQ-2005',
                'title'        => 'شراء خبز ومعلبات',
                'service_type' => 'grocery',
                'description'  => 'شراء خبز طازج ومعلبات من البقالة المجاورة.',
                'location'     => 'حي تل الهوا - غزة',
                'status'       => ServiceRequest::STATUS_PENDING_CONFIRMATION,
                'scheduled_at' => now()->subHours(3),
                'provider_id'  => $providerProfile?->id,
                'accepted_at'  => now()->subDays(1),
                'assigned_at'  => now()->subHours(8),
                'started_at'   => now()->subHours(4),
            ],

            // ═══════ 6. طلب مكتمل (completed) ═══════
            [
                'public_id'    => '#REQ-2006',
                'title'        => 'مرافقة إلى صلاة الجمعة',
                'service_type' => 'companion',
                'description'  => 'مرافقتي إلى المسجد القريب لصلاة الجمعة والعودة.',
                'location'     => 'مسجد العمري الكبير - غزة',
                'status'       => ServiceRequest::STATUS_COMPLETED,
                'scheduled_at' => now()->subDays(3),
                'provider_id'  => $providerProfile?->id,
                'accepted_at'  => now()->subDays(4),
                'assigned_at'  => now()->subDays(3)->setHour(8),
                'started_at'   => now()->subDays(3)->setHour(11),
                'completed_at' => now()->subDays(3)->setHour(13),
            ],

            // ═══════ 7. طلب لم يتوفر مقدم خدمة (no_provider_found) ═══════
            [
                'public_id'    => '#REQ-2007',
                'title'        => 'مساعدة في حمل أغراض ثقيلة',
                'service_type' => 'home_help',
                'description'  => 'أحتاج مساعدة في نقل بعض الأغراض الثقيلة من الطابق الأول إلى الثالث.',
                'location'     => 'برج المقوسي - غزة',
                'status'       => ServiceRequest::STATUS_NO_PROVIDER_FOUND,
                'scheduled_at' => now()->subDay(),
                'provider_id'  => null,
            ],

            // ═══════ 8. طلب اعتذر عنه مقدم الخدمة (provider_apologized) ═══════
            [
                'public_id'    => '#REQ-2008',
                'title'        => 'إحضار دواء من مستودع الأدوية',
                'service_type' => 'medicine',
                'description'  => 'إحضار دواء خاص من مستودع الأدوية الحكومي.',
                'location'     => 'مستودع الأدوية المركزي - غزة',
                'status'       => ServiceRequest::STATUS_PROVIDER_APOLOGIZED,
                'scheduled_at' => now()->addDay()->setHour(11)->setMinute(0),
                'provider_id'  => null,
                'previous_provider_id' => $providerProfile?->id,
            ],

            // ═══════ 9. طلب ملغى (cancelled) ═══════
            [
                'public_id'       => '#REQ-2009',
                'title'           => 'زيارة اجتماعية',
                'service_type'    => 'social',
                'description'     => 'زيارة للمحادثة والمؤانسة.',
                'location'        => 'المنزل - حي الشيخ رضوان',
                'status'          => ServiceRequest::STATUS_CANCELLED,
                'scheduled_at'    => now()->subDays(2),
                'provider_id'     => null,
                'cancelled_at'    => now()->subDays(2),
                'cancellation_reason' => 'تحسنت حالتي ولله الحمد ولم أعد بحاجة للزيارة.',
            ],

            // ═══════ 10. طلب تأخر مقدم الخدمة (provider_delayed) ═══════
            [
                'public_id'    => '#REQ-2010',
                'title'        => 'شراء أدوية عاجلة',
                'service_type' => 'medicine',
                'description'  => 'أحتاج أدوية عاجلة من الصيدلية المناوبة.',
                'location'     => 'صيدلية النور - شارع الجلاء',
                'status'       => ServiceRequest::STATUS_PROVIDER_DELAYED,
                'scheduled_at' => now()->subHours(2),
                'provider_id'  => $providerProfile?->id,
                'accepted_at'  => now()->subHours(6),
                'assigned_at'  => now()->subHours(4),
                'incident_type' => 'delay',
            ],

            // ═══════ 11. طلب مكتمل قديم (completed) — لتعدد البيانات ═══════
            [
                'public_id'    => '#REQ-2011',
                'title'        => 'شراء أغراض مدرسية للأحفاد',
                'service_type' => 'grocery',
                'description'  => 'شراء دفاتر وأقلام ومستلزمات مدرسية من مكتبة الأقصى.',
                'location'     => 'مكتبة الأقصى - شارع عمر المختار',
                'status'       => ServiceRequest::STATUS_COMPLETED,
                'scheduled_at' => now()->subWeek(),
                'provider_id'  => $providerProfile?->id,
                'accepted_at'  => now()->subWeek()->subDay(),
                'assigned_at'  => now()->subWeek()->subHours(3),
                'started_at'   => now()->subWeek(),
                'completed_at' => now()->subWeek()->addHours(2),
            ],
        ];

        foreach ($requests as $data) {
            $data['elder_id'] = $elderProfile->id;

            ServiceRequest::updateOrCreate(
                ['public_id' => $data['public_id']],
                $data
            );
        }

        // زراعة إشعارات تجريبية تغطي مختلف التنبيهات لكبير السن
        $elderUserId = $elderProfile->user_id;

        $demoNotifications = [
            [
                'user_id' => $elderUserId,
                'type' => 'orange',
                'message' => 'أنهى المتطوع محمد أحمد المهمة (#REQ-2005) - قراءة ومؤانسة، بانتظار تأكيدك وإضافة تقييمك.',
                'is_read' => false,
            ],
            [
                'user_id' => $elderUserId,
                'type' => 'green',
                'message' => 'تم توكيل المتطوع محمد أحمد لطلبك (#REQ-2003) إحضار أدوية من الصيدلية. يمكنك الاتصال به الآن.',
                'is_read' => false,
            ],
            [
                'user_id' => $elderUserId,
                'type' => 'green',
                'message' => 'بدأ المتطوع محمد أحمد بتنفيذ الخدمة ميدانياً (#REQ-2004) - تصليح كرسي متحرك.',
                'is_read' => false,
            ],
            [
                'user_id' => $elderUserId,
                'type' => 'red',
                'message' => 'اعتذر المتطوع عن تنفيذ الطلب #REQ-2009. تم إعادة نشر الطلب للبحث عن متطوع آخر.',
                'is_read' => false,
            ],
            [
                'user_id' => $elderUserId,
                'type' => 'orange',
                'message' => 'تنبيه: تأخر المتطوع عن الموعد المحدد للطلب #REQ-2010 (شراء أدوية عاجلة). يمكنك البحث عن بديل.',
                'is_read' => false,
            ],
            [
                'user_id' => $elderUserId,
                'type' => 'red',
                'message' => 'تنبيه إداري: بلاغ المشكلة للطلب #REQ-2007 قيد المراجعة والتدقيق الإداري.',
                'is_read' => true,
            ],
            [
                'user_id' => $elderUserId,
                'type' => 'green',
                'message' => 'تم اكتمال وتقييم طلب الخدمة #REQ-2006 (استشارة صحية هاتفية) بنجاح. شكراً لاستخدامك أنيس.',
                'is_read' => true,
            ],
        ];

        foreach ($demoNotifications as $notif) {
            Notification::firstOrCreate(
                ['user_id' => $notif['user_id'], 'message' => $notif['message']],
                $notif
            );
        }

        $this->command->info('✅ تم زراعة ' . count($requests) . ' طلب خدمة و ' . count($demoNotifications) . ' إشعارات تجريبية بنجاح!');
    }
}
