<?php

namespace Tests\Feature;

use App\Models\ProviderSetting;
use App\Models\ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VolunteerTaskTest extends TestCase
{
    use RefreshDatabase;

    private User $provider;
    private User $elderly;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = User::factory()->create([
            'name' => 'محمد أحمد',
            'email' => 'mohammed@ihsan.com',
            'account_type' => 'volunteer',
            'email_verified_at' => now(),
        ]);

        $this->elderly = User::factory()->create([
            'name' => 'الحاج أبو أحمد',
            'email' => 'elderly@ihsan.com',
            'account_type' => 'elderly',
            'email_verified_at' => now(),
        ]);
    }

    /**
     * اختبار الوصول إلى لوحة تحكم مقدم الخدمة.
     */
    public function test_provider_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->provider)->get(route('provider.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('مرحبًا محمد أحمد');
        $response->assertSee('طلبات متاحة');
    }

    /**
     * اختبار استعراض وتصفية الطلبات المتاحة.
     */
    public function test_provider_can_view_and_filter_available_requests(): void
    {
        $req = ServiceRequest::create([
            'public_id' => '#REQ-2001',
            'user_id' => $this->elderly->id,
            'title' => 'شراء أغراض منزلية',
            'service_type' => 'grocery',
            'description' => 'شراء مستلزمات من السوبرماركت',
            'location' => 'حي الرمال',
            'district' => 'حي الرمال',
            'distance_km' => 1.8,
            'scheduled_at' => Carbon::tomorrow()->setTime(10, 0),
            'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
            'attempts_count' => 1,
        ]);

        $response = $this->actingAs($this->provider)->get(route('provider.available'));

        $response->assertStatus(200);
        $response->assertSee('شراء أغراض منزلية');
        $response->assertSee('1.8 كم');
        $response->assertSee('يظهر العنوان الدقيق وبيانات التواصل بعد قبول الطلب');
    }

    /**
     * اختبار قبول الطلب وإسناده حصرياً لمقدم الخدمة.
     */
    public function test_provider_can_accept_available_request(): void
    {
        $req = ServiceRequest::create([
            'public_id' => '#REQ-2002',
            'user_id' => $this->elderly->id,
            'title' => 'إحضار دواء',
            'service_type' => 'medicine',
            'description' => 'إحضار وصفة طبية',
            'location' => 'حي الرمال - شارع الوحدة',
            'district' => 'حي الرمال',
            'scheduled_at' => Carbon::tomorrow()->setTime(14, 0),
            'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
            'attempts_count' => 1,
        ]);

        $response = $this->actingAs($this->provider)->post(route('provider.tasks.accept', $req));

        $response->assertRedirect(route('provider.tasks', ['tab' => 'upcoming']));
        $this->assertDatabaseHas('service_requests', [
            'id' => $req->id,
            'assigned_provider_id' => $this->provider->id,
            'status' => ServiceRequest::STATUS_ACCEPTED,
        ]);
        $this->assertDatabaseHas('request_attempts', [
            'service_request_id' => $req->id,
            'provider_id' => $this->provider->id,
            'outcome' => 'accepted',
        ]);
    }

    /**
     * اختبار تجاوز الطلب دون التأثير على التقييم.
     */
    public function test_provider_can_dismiss_request_without_penalty(): void
    {
        $req = ServiceRequest::create([
            'public_id' => '#REQ-2003',
            'user_id' => $this->elderly->id,
            'title' => 'مساعدة منزلية',
            'service_type' => 'home_help',
            'description' => 'ترتيب أغراض',
            'location' => 'حي النصر',
            'scheduled_at' => Carbon::tomorrow()->setTime(16, 0),
            'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
            'attempts_count' => 1,
        ]);

        $response = $this->actingAs($this->provider)->post(route('provider.tasks.dismiss', $req));

        $response->assertRedirect(route('provider.available'));
        $this->assertDatabaseHas('provider_dismissed_requests', [
            'user_id' => $this->provider->id,
            'service_request_id' => $req->id,
        ]);
    }

    /**
     * اختبار سير العمليات المتسلسل: في الطريق -> وصل -> قيد التنفيذ -> إنهاء الخدمة.
     */
    public function test_provider_full_execution_lifecycle(): void
    {
        $req = ServiceRequest::create([
            'public_id' => '#REQ-2004',
            'user_id' => $this->elderly->id,
            'assigned_provider_id' => $this->provider->id,
            'title' => 'مرافقة طبية',
            'service_type' => 'medical_escort',
            'description' => 'مرافقة إلى العيادة',
            'location' => 'حي الرمال',
            'scheduled_at' => Carbon::now()->addHour(),
            'status' => ServiceRequest::STATUS_ACCEPTED,
            'accepted_at' => now(),
            'attempts_count' => 1,
        ]);

        // 1. بدء التوجه (في الطريق)
        $respHeading = $this->actingAs($this->provider)->post(route('provider.tasks.start-heading', $req));
        $respHeading->assertRedirect(route('provider.tasks', ['tab' => 'upcoming']));
        $this->assertEquals(ServiceRequest::STATUS_ON_THE_WAY, $req->fresh()->status);

        // 2. تأكيد الوصول (وصل)
        $respArrival = $this->actingAs($this->provider)->post(route('provider.tasks.confirm-arrival', $req));
        $respArrival->assertRedirect(route('provider.tasks', ['tab' => 'upcoming']));
        $this->assertEquals(ServiceRequest::STATUS_ARRIVED, $req->fresh()->status);

        // 3. بدء الخدمة (قيد التنفيذ)
        $respStart = $this->actingAs($this->provider)->post(route('provider.tasks.start-service', $req));
        $respStart->assertRedirect(route('provider.tasks', ['tab' => 'in_progress']));
        $this->assertEquals(ServiceRequest::STATUS_IN_PROGRESS, $req->fresh()->status);

        // 4. إنهاء الخدمة (بانتظار التأكيد)
        $respFinish = $this->actingAs($this->provider)->post(route('provider.tasks.finish-service', $req), [
            'completion_notes' => 'تمت مرافقة كبير السن وعودته للمنزل بسلام.',
        ]);
        $respFinish->assertRedirect(route('provider.tasks', ['tab' => 'pending_confirmation']));
        $this->assertEquals(ServiceRequest::STATUS_PENDING_CONFIRMATION, $req->fresh()->status);
        $this->assertEquals('تمت مرافقة كبير السن وعودته للمنزل بسلام.', $req->fresh()->completion_notes);
    }

    /**
     * اختبار الإبلاغ عن تأخير وخصم نقاط الالتزام.
     */
    public function test_provider_reports_delay_and_deducts_commitment(): void
    {
        $setting = $this->provider->getOrCreateProviderSetting();
        $initialScore = $setting->commitment_score;

        $req = ServiceRequest::create([
            'public_id' => '#REQ-2005',
            'user_id' => $this->elderly->id,
            'assigned_provider_id' => $this->provider->id,
            'title' => 'شراء دواء',
            'service_type' => 'medicine',
            'description' => 'وصفة طبية',
            'location' => 'حي الرمال',
            'scheduled_at' => Carbon::now()->addHour(),
            'status' => ServiceRequest::STATUS_ACCEPTED,
            'attempts_count' => 1,
        ]);

        // تأخير 20 دقيقة -> خصم نقطتين (-2)
        $response = $this->actingAs($this->provider)->post(route('provider.tasks.report-delay', $req), [
            'delay_minutes' => 20,
            'delay_reason' => 'ازدحام مروري خانق في شارع عمر المختار',
        ]);

        $response->assertRedirect(route('provider.tasks', ['tab' => 'upcoming']));
        $this->assertEquals(ServiceRequest::STATUS_PROVIDER_DELAYED, $req->fresh()->status);
        $this->assertEquals($initialScore - 2, $setting->fresh()->commitment_score);
    }

    /**
     * اختبار الاعتذار عن الطلب وفصل الإسناد وإعادة النشر.
     */
    public function test_provider_can_apologize_and_republish_request(): void
    {
        $setting = $this->provider->getOrCreateProviderSetting();
        $initialScore = $setting->commitment_score;

        $req = ServiceRequest::create([
            'public_id' => '#REQ-2006',
            'user_id' => $this->elderly->id,
            'assigned_provider_id' => $this->provider->id,
            'title' => 'شراء أغراض',
            'service_type' => 'grocery',
            'description' => 'احتياجات منزلية',
            'location' => 'حي الرمال',
            'scheduled_at' => Carbon::now()->addHours(5), // أقل من 24 ساعة -> خصم 5 نقاط
            'status' => ServiceRequest::STATUS_ACCEPTED,
            'attempts_count' => 1,
        ]);

        $response = $this->actingAs($this->provider)->post(route('provider.tasks.apologize', $req), [
            'apology_reason' => 'ظرف عائلي طارئ ومفاجئ يمنعني من الحضور.',
        ]);

        $response->assertRedirect(route('provider.tasks', ['tab' => 'upcoming']));
        $this->assertEquals(ServiceRequest::STATUS_PROVIDER_APOLOGIZED, $req->fresh()->status);
        $this->assertNull($req->fresh()->assigned_provider_id);
        $this->assertEquals(2, $req->fresh()->attempts_count);
        $this->assertEquals($initialScore - 5, $setting->fresh()->commitment_score);
    }

    /**
     * اختبار حفظ إعدادات التوفر والجدول الأسبوعي.
     */
    public function test_provider_can_update_availability_settings(): void
    {
        $response = $this->actingAs($this->provider)->post(route('provider.availability.update'), [
            'available_days' => ['sat', 'mon', 'wed'],
            'available_from' => '09:00',
            'available_to' => '17:00',
            'is_available' => 1,
            'offered_services' => ['grocery', 'medicine'],
            'service_city' => 'مدينة غزة',
            'coverage_radius_km' => 10,
        ]);

        $response->assertRedirect(route('provider.availability'));
        $this->assertDatabaseHas('provider_settings', [
            'user_id' => $this->provider->id,
            'service_city' => 'مدينة غزة',
            'coverage_radius_km' => 10,
            'is_available' => true,
        ]);
    }
}

