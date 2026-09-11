<?php

namespace Tests\Feature;

use App\Models\ElderProfile;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Batch2FrontendSecurityTest extends TestCase
{
    use RefreshDatabase;

    private User $elderUser;
    private ElderProfile $elderProfile;
    private User $providerUser;
    private ServiceProviderProfile $providerProfile;

    protected function setUp(): void
    {
        parent::setUp();

        // إنشاء كبير سن معتمد ومفعل
        $this->elderUser = User::factory()->create([
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);

        $this->elderProfile = ElderProfile::create([
            'user_id' => $this->elderUser->id,
            'full_name' => 'الحاج إبراهيم',
            'phone_number' => '0599001122',
            'birth_date' => '1950-01-01',
            'gender' => 'male',
            'address' => 'غزة - حي الرمال',
            'city' => 'غزة',
        ]);

        // إنشاء متطوع معتمد ومفعل
        $this->providerUser = User::factory()->create([
            'status' => 'approved',
            'email_verified_at' => now(),
        ]);

        $this->providerProfile = ServiceProviderProfile::create([
            'user_id' => $this->providerUser->id,
            'full_name' => 'خالد المتطوع',
            'phone_number' => '0599887766',
            'birth_date' => '1995-05-15',
            'gender' => 'male',
            'address' => 'غزة - شارع النصر',
            'city' => 'غزة',
            'id_number' => '401234567',
            'id_document_path' => 'docs/id.pdf',
            'good_conduct_cert_path' => 'docs/conduct.pdf',
            'tier' => 1,
            'is_available' => true,
        ]);
    }

    /**
     * 1. التأكد أن زر الإلغاء لا يظهر فعليًا في HTML الناتج عند الحالات المحظورة
     * (assigned, in_progress, pending_confirmation) ويظهر في pending_acceptance
     */
    public function test_cancel_button_does_not_render_in_html_for_forbidden_statuses(): void
    {
        // طلب بحالة مسند assigned
        $assignedReq = ServiceRequest::create([
            'public_id' => 'REQ-2001',
            'elder_id' => $this->elderProfile->id,
            'provider_id' => $this->providerProfile->id,
            'title' => 'مرافقة إلى المستشفى',
            'service_type' => 'companion',
            'description' => 'مرافقة للمركز الصحي',
            'location' => 'حي النصر',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceRequest::STATUS_ASSIGNED,
            'assigned_at' => now(),
        ]);

        // طلب بحالة قيد التنفيذ in_progress
        $inProgressReq = ServiceRequest::create([
            'public_id' => 'REQ-2002',
            'elder_id' => $this->elderProfile->id,
            'provider_id' => $this->providerProfile->id,
            'title' => 'شراء دواء عاجل',
            'service_type' => 'medical',
            'description' => 'أحتاج دواء الضغط',
            'location' => 'حي الرمال',
            'scheduled_at' => now()->addHours(2),
            'status' => ServiceRequest::STATUS_IN_PROGRESS,
            'started_at' => now(),
        ]);

        // طلب بحالة بانتظار التأكيد pending_confirmation
        $pendingConfirmReq = ServiceRequest::create([
            'public_id' => 'REQ-2003',
            'elder_id' => $this->elderProfile->id,
            'provider_id' => $this->providerProfile->id,
            'title' => 'زيارة اجتماعية',
            'service_type' => 'social',
            'description' => 'زيارة ودعم اجتماعي',
            'location' => 'حي الصبرة',
            'scheduled_at' => now()->subHour(),
            'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
        ]);

        $response = $this->actingAs($this->elderUser)->get(route('service-requests.index', ['tab' => 'active']));

        $response->assertStatus(200);

        // التحقق من عدم وجود زر إلغاء الطلبات الثلاثة في الـ HTML
        $html = $response->getContent();
        $this->assertStringNotContainsString("requests/{$assignedReq->id}/cancel", $html);
        $this->assertStringNotContainsString("requests/{$inProgressReq->id}/cancel", $html);
        $this->assertStringNotContainsString("requests/{$pendingConfirmReq->id}/cancel", $html);

        // بالمقابل: طلب جديد بحالة pending_acceptance يظهر له زر الإلغاء
        $pendingReq = ServiceRequest::create([
            'public_id' => 'REQ-2004',
            'elder_id' => $this->elderProfile->id,
            'title' => 'شراء مقاضي عامة',
            'service_type' => 'grocery',
            'description' => 'شراء خضروات',
            'location' => 'حي الدرج',
            'scheduled_at' => now()->addDays(2),
            'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
        ]);

        $response2 = $this->actingAs($this->elderUser)->get(route('service-requests.index', ['tab' => 'active']));
        $response2->assertStatus(200);
        $response2->assertSee("requests/{$pendingReq->id}/cancel");
        $response2->assertSee('إلغاء الطلب');
    }

    /**
     * 2. التأكد أن رقم هاتف الطرف الآخر لا يظهر فعليًا في HTML قبل حالة assigned
     * وتظهر رسالة بديلة "سيظهر رقم التواصل بعد تأكيد الإسناد"
     */
    public function test_contact_phone_number_is_masked_before_assigned_status(): void
    {
        $providerSecretPhone = '0599887766';

        // حالة pending_acceptance
        $reqPending = ServiceRequest::create([
            'public_id' => 'REQ-3001',
            'elder_id' => $this->elderProfile->id,
            'title' => 'طلب مساعدة بدون متطوع',
            'service_type' => 'companion',
            'description' => 'مرافقة عامة',
            'location' => 'غزة',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
        ]);

        // حالة accepted (تم القبول المبدئي قبل التوكيل الرسمي assigned)
        $reqAccepted = ServiceRequest::create([
            'public_id' => 'REQ-3002',
            'elder_id' => $this->elderProfile->id,
            'provider_id' => $this->providerProfile->id,
            'title' => 'طلب تم قبوله مبدئياً',
            'service_type' => 'grocery',
            'description' => 'شراء تموينات',
            'location' => 'غزة',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceRequest::STATUS_ACCEPTED,
            'accepted_at' => now(),
        ]);

        $response = $this->actingAs($this->elderUser)->get(route('service-requests.index', ['tab' => 'active']));

        $response->assertStatus(200);
        // يجب ألا يظهر رقم هاتف المتطوع إطلاقاً في هذه الحالات
        $response->assertDontSee($providerSecretPhone);
        // ويجب أن يظهر النص البديل التوضيحي
        $response->assertSee('سيظهر رقم التواصل بعد تأكيد الإسناد');

        // الآن نقوم بتحويل الطلب إلى assigned
        $reqAccepted->update([
            'status' => ServiceRequest::STATUS_ASSIGNED,
            'assigned_at' => now(),
        ]);

        $responseAssigned = $this->actingAs($this->elderUser)->get(route('service-requests.index', ['tab' => 'active']));
        $responseAssigned->assertStatus(200);
        // الآن فقط يظهر زر التواصل ومعه رقم الهاتف
        $responseAssigned->assertSee($providerSecretPhone);
        $responseAssigned->assertSee('تواصل مع المتطوع');
    }

    /**
     * 3. التأكد أن محاولة استدعاء cancel() مباشرة عبر HTTP request على طلب بحالة محظورة تُرفض برمز 403
     */
    public function test_direct_http_cancel_request_returns_403_for_forbidden_statuses(): void
    {
        // 1. طلب بحالة assigned
        $assignedReq = ServiceRequest::create([
            'public_id' => 'REQ-4001',
            'elder_id' => $this->elderProfile->id,
            'provider_id' => $this->providerProfile->id,
            'title' => 'طلب مسند',
            'service_type' => 'medical',
            'description' => 'شرح الطلب',
            'location' => 'غزة',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceRequest::STATUS_ASSIGNED,
        ]);

        $response1 = $this->actingAs($this->elderUser)->delete(route('service-requests.cancel', $assignedReq), [
            'cancellation_reason' => 'محاولة إلغاء غير مسموحة',
        ]);
        $response1->assertStatus(403);
        $this->assertEquals(ServiceRequest::STATUS_ASSIGNED, $assignedReq->fresh()->status);

        // 2. طلب بحالة in_progress
        $inProgressReq = ServiceRequest::create([
            'public_id' => 'REQ-4002',
            'elder_id' => $this->elderProfile->id,
            'provider_id' => $this->providerProfile->id,
            'title' => 'طلب قيد التنفيذ',
            'service_type' => 'companion',
            'description' => 'شرح الطلب',
            'location' => 'غزة',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceRequest::STATUS_IN_PROGRESS,
        ]);

        $response2 = $this->actingAs($this->elderUser)->delete(route('service-requests.cancel', $inProgressReq), [
            'cancellation_reason' => 'محاولة إلغاء غير مسموحة',
        ]);
        $response2->assertStatus(403);
        $this->assertEquals(ServiceRequest::STATUS_IN_PROGRESS, $inProgressReq->fresh()->status);

        // 3. طلب بحالة pending_confirmation
        $pendingConfirmReq = ServiceRequest::create([
            'public_id' => 'REQ-4003',
            'elder_id' => $this->elderProfile->id,
            'provider_id' => $this->providerProfile->id,
            'title' => 'طلب بانتظار التأكيد',
            'service_type' => 'social',
            'description' => 'شرح الطلب',
            'location' => 'غزة',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceRequest::STATUS_PENDING_CONFIRMATION,
        ]);

        $response3 = $this->actingAs($this->elderUser)->delete(route('service-requests.cancel', $pendingConfirmReq), [
            'cancellation_reason' => 'محاولة إلغاء غير مسموحة',
        ]);
        $response3->assertStatus(403);
        $this->assertEquals(ServiceRequest::STATUS_PENDING_CONFIRMATION, $pendingConfirmReq->fresh()->status);

        // 4. بالمقابل: طلب متاح pending_acceptance ينجح إلغاؤه (302 redirect)
        $allowedReq = ServiceRequest::create([
            'public_id' => 'REQ-4004',
            'elder_id' => $this->elderProfile->id,
            'title' => 'طلب متاح مسموح إلغاؤه',
            'service_type' => 'grocery',
            'description' => 'شرح الطلب',
            'location' => 'غزة',
            'scheduled_at' => now()->addDay(),
            'status' => ServiceRequest::STATUS_PENDING_ACCEPTANCE,
        ]);

        $response4 = $this->actingAs($this->elderUser)->delete(route('service-requests.cancel', $allowedReq), [
            'cancellation_reason' => 'لم أعد بحاجة للخدمة',
        ]);
        $response4->assertRedirect(route('service-requests.index', ['tab' => 'cancelled']));
        $this->assertEquals(ServiceRequest::STATUS_CANCELLED, $allowedReq->fresh()->status);
    }

    /**
     * 4. فحص شاشة تسجيل الدخول: الواجهة المقسمة، الحقول، توكن الحماية، وإظهار سبب الرفض
     */
    public function test_login_screen_renders_split_layout_and_shows_rejection_reason(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);

        // فحص حقول الإيميل وكلمة المرور وتوكن الحماية CSRF
        $response->assertSee('name="email"', false);
        $response->assertSee('type="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="_token"', false);
        $response->assertSee(route('password.request'));
        $response->assertSee(route('frontend.elderly.register'));
        $response->assertSee(route('frontend.volunteer.register'));

        // فحص ظهور رسالة سبب الرفض لحساب مرفوض
        $rejectedUser = User::factory()->create([
            'email' => 'rejected_user@example.com',
            'password' => bcrypt('password123'),
            'status' => 'rejected',
            'rejection_reason' => 'المستندات المرفقة غير واضحة',
            'email_verified_at' => now(),
        ]);

        $loginResponse = $this->post(route('login'), [
            'email' => 'rejected_user@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertSessionHasErrors(['email', 'rejection_reason']);

        // التحقق من أن متابعة إعادة التوجيه إلى صفحة الدخول تعرض سبب الرفض صراحة
        $followed = $this->get(route('login'));
        $followed->assertSee('المستندات المرفقة غير واضحة');
    }
}
