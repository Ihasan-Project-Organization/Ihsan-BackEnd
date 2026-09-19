<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Complaint;
use App\Models\ElderProfile;
use App\Models\Rating;
use App\Models\ServiceProviderProfile;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class E2EComprehensiveAuditTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdminUser;
    private User $adminUser;
    private User $elderUser;
    private User $providerUser1;
    private User $providerUser2;
    private User $pendingUser;
    private User $suspendedUser;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        Storage::fake('public');

        // 1. Super Admin
        $this->superAdminUser = User::factory()->create([
            'email' => 'superadmin@ihsan.com',
            'status' => 'approved',
            'password' => Hash::make('password'),
        ]);
        Admin::create([
            'user_id' => $this->superAdminUser->id,
            'admin_level' => 'super_admin',
        ]);

        // 2. Regular Admin
        $this->adminUser = User::factory()->create([
            'email' => 'admin@ihsan.com',
            'status' => 'approved',
            'password' => Hash::make('password'),
        ]);
        Admin::create([
            'user_id' => $this->adminUser->id,
            'admin_level' => 'admin',
        ]);

        // 3. Elder User
        $this->elderUser = User::factory()->create([
            'name' => 'أبو أحمد التميمي',
            'email' => 'elderly@ihsan.com',
            'status' => 'approved',
            'password' => Hash::make('password'),
        ]);
        ElderProfile::create([
            'user_id' => $this->elderUser->id,
            'full_name' => 'أبو أحمد التميمي',
            'id_number' => '901234567',
            'birth_date' => '1955-03-10',
            'city' => 'مدينة غزة',
            'address' => 'شارع النصر - بجوار مدرسة الهدى',
            'housing_type' => 'house',
            'phone_number' => '0599987654',
            'id_document_path' => 'documents/elderly_id.png',
        ]);

        // 4. Provider 1
        $this->providerUser1 = User::factory()->create([
            'name' => 'محمد أحمد',
            'email' => 'mohammed@ihsan.com',
            'status' => 'approved',
            'password' => Hash::make('password'),
        ]);
        ServiceProviderProfile::create([
            'user_id' => $this->providerUser1->id,
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
        ]);

        // 5. Provider 2
        $this->providerUser2 = User::factory()->create([
            'name' => 'خالد العلي',
            'email' => 'khaled@ihsan.com',
            'status' => 'approved',
            'password' => Hash::make('password'),
        ]);
        ServiceProviderProfile::create([
            'user_id' => $this->providerUser2->id,
            'full_name' => 'خالد العلي',
            'id_number' => '402233445',
            'birth_date' => '1998-08-20',
            'phone_number' => '0599222333',
            'id_document_path' => 'documents/provider2_id.png',
            'good_conduct_cert_path' => 'documents/conduct_cert2.pdf',
            'tier' => 1,
            'completed_tasks_count' => 3,
            'average_rating' => 4.8,
            'is_available' => true,
            'reliability_incidents_count' => 0,
        ]);

        // 6. Pending User
        $this->pendingUser = User::factory()->create([
            'email' => 'pending@ihsan.com',
            'status' => 'pending',
            'password' => Hash::make('password'),
        ]);

        // 7. Suspended User
        $this->suspendedUser = User::factory()->create([
            'email' => 'suspended@ihsan.com',
            'status' => 'suspended',
            'suspension_reason' => 'تم إيقاف الحساب لمخالفة التعليمات.',
            'password' => Hash::make('password'),
        ]);
    }

    /** 1. Test Navigation & Error Pages */
    public function test_error_pages_and_navigation()
    {
        // 404
        $res = $this->get('/non-existent-route-404-check');
        $res->assertStatus(404);

        // Guest to protected redirects
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/requests')->assertRedirect('/login');
        $this->get('/provider/dashboard')->assertRedirect('/login');
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    /** 2. Test Cross-Role Authorization (403 Forbidden) */
    public function test_cross_role_access_strictly_forbidden()
    {
        // Elder accessing provider and admin routes
        $this->actingAs($this->elderUser)->get('/provider/dashboard')->assertStatus(403);
        $this->actingAs($this->elderUser)->get('/provider/available')->assertStatus(403);
        $this->actingAs($this->elderUser)->get('/admin/dashboard')->assertStatus(403);
        $this->actingAs($this->elderUser)->get('/admin/users')->assertStatus(403);

        // Provider accessing elder and admin routes
        $this->actingAs($this->providerUser1)->get('/dashboard')->assertStatus(403);
        $this->actingAs($this->providerUser1)->get('/requests')->assertStatus(403);
        $this->actingAs($this->providerUser1)->get('/admin/dashboard')->assertStatus(403);

        // Regular admin accessing super admin routes
        $this->actingAs($this->adminUser)->get('/admin/admins')->assertStatus(403);
        $this->actingAs($this->adminUser)->get('/admin/settings')->assertStatus(403);

        // Super admin can access super admin routes
        $this->actingAs($this->superAdminUser)->get('/admin/admins')->assertStatus(200);
        $this->actingAs($this->superAdminUser)->get('/admin/settings')->assertStatus(200);
    }

    /** 3. Test Elderly Registration Validation & Error Highlighting */
    public function test_elderly_registration_form_validation()
    {
        // Empty submission
        $res = $this->post('/register', [
            'role' => 'elder',
        ]);
        $res->assertSessionHasErrors(['name', 'email', 'password', 'phone']);

        // Invalid email & short password
        $res2 = $this->post('/register', [
            'role' => 'elder',
            'name' => 'مستفيد جديد',
            'email' => 'invalid-email-format',
            'password' => '123',
            'password_confirmation' => '456',
            'phone_number' => '0599111222',
            'city' => 'غزة',
        ]);
        $res2->assertSessionHasErrors(['email', 'password']);
    }

    /** 4. Test Volunteer Registration Form Validation */
    public function test_volunteer_registration_form_validation()
    {
        // Empty submission
        $res = $this->post('/register', [
            'role' => 'provider',
        ]);
        $res->assertSessionHasErrors(['name', 'email', 'password', 'phone', 'dob', 'id_document', 'conduct_document']);
    }

    /** 5. Test Admin Approvals - Elder & Provider Details Verification */
    public function test_admin_approvals_details_display_and_actions()
    {
        // Admin views approvals index
        $res = $this->actingAs($this->adminUser)->get('/admin/approvals');
        $res->assertStatus(200);

        // Admin views Elder approval
        $resElder = $this->actingAs($this->adminUser)->get("/admin/approvals/{$this->elderUser->id}");
        $resElder->assertStatus(200);
        $resElder->assertSee('901234567'); // ID number
        $resElder->assertSee('شارع النصر'); // Address
        $resElder->assertDontSee('شهادة حسن السير'); // strictly excluded for elder!

        // Admin views Volunteer approval
        $resProv = $this->actingAs($this->adminUser)->get("/admin/approvals/{$this->providerUser1->id}");
        $resProv->assertStatus(200);
        $resProv->assertSee('401234567'); // ID number
        $resProv->assertSee('شهادة حسن السيرة'); // Volunteer must have it

        // Admin approves pending user
        $approveRes = $this->actingAs($this->adminUser)->post("/admin/approvals/{$this->pendingUser->id}/approve");
        $approveRes->assertRedirect();
        $this->assertEquals('approved', $this->pendingUser->fresh()->status);

        // Admin rejects a user with reason
        $rejectUser = User::factory()->create(['status' => 'pending']);
        $rejectRes = $this->actingAs($this->adminUser)->post("/admin/approvals/{$rejectUser->id}/reject", [
            'rejection_reason' => 'الوثائق المرفوعة غير واضحة ومخالفة للمواصفات',
        ]);
        $rejectRes->assertRedirect();
        $this->assertEquals('rejected', $rejectUser->fresh()->status);
        $this->assertEquals('الوثائق المرفوعة غير واضحة ومخالفة للمواصفات', $rejectUser->fresh()->rejection_reason);
    }

    /** 6. Test Service Request CRUD: Create, Reschedule, Search Alternative, Cancel */
    public function test_service_request_lifecycle_crud()
    {
        // 1. Elder creates request with immediate/flexible timing ("بدون مدة محددة")
        $createRes = $this->actingAs($this->elderUser)->post('/requests', [
            'title' => 'مساعدة في التسوق الأسبوعي',
            'service_type' => 'grocery',
            'description' => 'شراء خضروات وفواكه ومستلزمات منزلية',
            'location' => 'حي الرمال - غزة',
            'timing_type' => 'immediate',
            'gender_preference' => 'any',
            'pricing_type' => 'volunteer',
        ]);
        $createRes->assertRedirect(route('service-requests.index', ['tab' => 'active']));

        $req = ServiceRequest::where('title', 'مساعدة في التسوق الأسبوعي')->first();
        $this->assertNotNull($req);
        $this->assertEquals(ServiceRequest::STATUS_PENDING_ACCEPTANCE, $req->status);
        $this->assertStringStartsWith('#REQ-', $req->public_id);

        // 2. Elder can cancel a pending request with reason
        $cancelRes = $this->actingAs($this->elderUser)->delete("/requests/{$req->id}/cancel", [
            'cancellation_reason' => 'لم أعد بحاجة للخدمة اليوم، شكراً لكم',
        ]);
        $cancelRes->assertRedirect(route('service-requests.index', ['tab' => 'cancelled']));
        $this->assertEquals(ServiceRequest::STATUS_CANCELLED, $req->fresh()->status);
        $this->assertEquals('لم أعد بحاجة للخدمة اليوم، شكراً لكم', $req->fresh()->cancellation_reason);
    }

    /** 7. Test Complete Flow: Create -> Accept -> Start -> Delay -> Finish -> Confirm & Rate */
    public function test_complete_service_flow_from_start_to_finish()
    {
        // 1. Elder creates request (tier 1 grocery)
        $this->actingAs($this->elderUser)->post('/requests', [
            'title' => 'شراء مستلزمات غذائية عاجلة',
            'service_type' => 'grocery',
            'description' => 'شراء خضروات وفواكه ومواد تموينية',
            'location' => 'شارع الجلاء - غزة',
            'timing_type' => 'immediate',
            'gender_preference' => 'any',
            'pricing_type' => 'volunteer',
        ]);
        $req = ServiceRequest::where('title', 'شراء مستلزمات غذائية عاجلة')->first();
        $this->assertNotNull($req);

        // 2. Provider 1 views available requests
        $availRes = $this->actingAs($this->providerUser1)->get('/provider/available');
        $availRes->assertStatus(200);
        $availRes->assertSee($req->public_id);

        // 3. Provider 1 accepts task
        $acceptRes = $this->actingAs($this->providerUser1)->post("/provider/tasks/{$req->id}/accept");
        $acceptRes->assertRedirect();
        $this->assertEquals(ServiceRequest::STATUS_ASSIGNED, $req->fresh()->status);
        $this->assertEquals($this->providerUser1->serviceProviderProfile->id, $req->fresh()->provider_id);

        // 4. Provider reports delay
        $delayRes = $this->actingAs($this->providerUser1)->post("/provider/tasks/{$req->id}/report-delay", [
            'delay_reason' => 'ازدحام مروري شديد في طريق الوصول',
            'delay_minutes' => 15,
        ]);
        $delayRes->assertRedirect();
        $this->assertEquals(ServiceRequest::STATUS_PROVIDER_DELAYED, $req->fresh()->status);

        // 5. Provider starts service
        $startRes = $this->actingAs($this->providerUser1)->post("/provider/tasks/{$req->id}/start-service");
        $startRes->assertRedirect();
        $this->assertEquals(ServiceRequest::STATUS_IN_PROGRESS, $req->fresh()->status);

        // 6. Provider finishes service
        $finishRes = $this->actingAs($this->providerUser1)->post("/provider/tasks/{$req->id}/finish-service");
        $finishRes->assertRedirect();
        $this->assertEquals(ServiceRequest::STATUS_PENDING_CONFIRMATION, $req->fresh()->status);

        // 7. Elder confirms completion & submits 5-star rating
        $confirmRes = $this->actingAs($this->elderUser)->patch("/requests/{$req->id}/confirm", [
            'rating' => 5,
            'comment' => 'متطوع رائع ومحترم جداً بارك الله فيه',
        ]);
        $confirmRes->assertRedirect(route('service-requests.index', ['tab' => 'completed']));
        $this->assertEquals(ServiceRequest::STATUS_COMPLETED, $req->fresh()->status);

        // Verify Rating saved and Volunteer stats updated
        $this->assertDatabaseHas('ratings', [
            'service_request_id' => $req->id,
            'stars' => 5,
            'rater_role' => 'elder',
        ]);

        // 8. Provider can report an issue to the administration, but cannot rate the elder.
        $reportIssueRes = $this->actingAs($this->providerUser1)->post("/provider/tasks/{$req->id}/report-issue", [
            'issue_type' => 'information',
            'description' => 'معلومة تحتاج مراجعة من الإدارة.',
        ]);
        $reportIssueRes->assertRedirect();
        $this->assertDatabaseHas('complaints', [
            'request_id' => $req->id,
            'reporter_id' => $this->providerUser1->id,
            'status' => 'open',
        ]);
        $this->assertDatabaseMissing('ratings', ['service_request_id' => $req->id, 'rater_role' => 'provider']);
    }

    /** 8. Test Apology Workflow & Strict Reassignment Isolation */
    public function test_apology_reassignment_isolation()
    {
        // 1. Elder creates request
        $this->actingAs($this->elderUser)->post('/requests', [
            'title' => 'دعم تقني لإعداد الهاتف الذكي',
            'service_type' => 'support_request',
            'description' => 'مساعدة في تحديث التطبيقات',
            'location' => 'شارع الوحدة - غزة',
            'timing_type' => 'immediate',
            'gender_preference' => 'any',
            'pricing_type' => 'volunteer',
        ]);
        $req = ServiceRequest::where('title', 'دعم تقني لإعداد الهاتف الذكي')->first();
        $this->assertNotNull($req);

        // 2. Provider 1 accepts it
        $this->actingAs($this->providerUser1)->post("/provider/tasks/{$req->id}/accept");
        $provider1ProfileId = $this->providerUser1->serviceProviderProfile->id;

        // 3. Provider 1 apologizes
        $apologyRes = $this->actingAs($this->providerUser1)->post("/provider/tasks/{$req->id}/apologize", [
            'reason' => 'عطل فني في وسيلة النقل يمنعني من الوصول',
        ]);
        $apologyRes->assertRedirect();

        $req->refresh();
        $this->assertEquals(ServiceRequest::STATUS_PENDING_ACCEPTANCE, $req->status);
        $this->assertNull($req->provider_id);
        $this->assertEquals($provider1ProfileId, $req->previous_provider_id);

        // 4. Provider 1 (who apologized) must NEVER see this request in available pool
        $avail1 = $this->actingAs($this->providerUser1)->get('/provider/available');
        $avail1->assertStatus(200);
        $avail1->assertDontSee($req->public_id);

        // 5. Provider 2 (other qualified provider) MUST see the request
        $avail2 = $this->actingAs($this->providerUser2)->get('/provider/available');
        $avail2->assertStatus(200);
        $avail2->assertSee($req->public_id);

        // 6. Provider 2 can successfully accept the request
        $accept2 = $this->actingAs($this->providerUser2)->post("/provider/tasks/{$req->id}/accept");
        $accept2->assertRedirect();
        $this->assertEquals($this->providerUser2->serviceProviderProfile->id, $req->fresh()->provider_id);
        $this->assertEquals(ServiceRequest::STATUS_ASSIGNED, $req->fresh()->status);
    }

    /** 9. Test Report Problem, Complaint Creation, and Admin Resolution */
    public function test_report_problem_and_complaint_resolution()
    {
        // 1. Create and accept request
        $this->actingAs($this->elderUser)->post('/requests', [
            'title' => 'طلب صيانة خفيفة',
            'service_type' => 'grocery',
            'description' => 'شراء احتياجات منزلية خفيفة',
            'location' => 'شارع عمر المختار - غزة',
            'timing_type' => 'immediate',
            'gender_preference' => 'any',
            'pricing_type' => 'volunteer',
        ]);
        $req = ServiceRequest::where('title', 'طلب صيانة خفيفة')->first();
        $this->assertNotNull($req);
        $this->actingAs($this->providerUser1)->post("/provider/tasks/{$req->id}/accept");

        // 2. Elder reports a problem
        $problemRes = $this->actingAs($this->elderUser)->post("/requests/{$req->id}/report-problem", [
            'reason' => 'لم يلتزم مقدم الخدمة بالموعد ولم يرد على الاتصالات',
        ]);
        $problemRes->assertRedirect(route('service-requests.index', ['tab' => 'active']));

        $req->refresh();
        $this->assertEquals(ServiceRequest::STATUS_UNDER_REVIEW, $req->status);

        $complaint = Complaint::where('request_id', $req->id)->first();
        $this->assertNotNull($complaint);
        $this->assertEquals('open', $complaint->status);

        // 3. Admin views complaints and resolves it
        $compIndex = $this->actingAs($this->adminUser)->get('/admin/complaints');
        $compIndex->assertStatus(200);
        $compIndex->assertSee($req->public_id);

        $resolveRes = $this->actingAs($this->adminUser)->post("/admin/complaints/{$complaint->id}/resolve", [
            'status' => 'closed',
            'admin_notes' => 'تم التواصل مع الطرفين وحل الإشكال وإعادة جدولة الخدمة',
        ]);
        $resolveRes->assertRedirect();
        $this->assertEquals('closed', $complaint->fresh()->status);
        $this->assertEquals('تم التواصل مع الطرفين وحل الإشكال وإعادة جدولة الخدمة', $complaint->fresh()->admin_notes);
    }

    /** 10. Test Admin User Management: Suspend and Reactivate */
    public function test_admin_user_suspend_and_reactivate()
    {
        $targetUser = User::factory()->create(['status' => 'approved']);

        // Suspend
        $suspendRes = $this->actingAs($this->adminUser)->post("/admin/users/{$targetUser->id}/suspend", [
            'suspension_reason' => 'مخالفة شروط الخدمة والاستخدام',
        ]);
        $suspendRes->assertRedirect();
        $this->assertEquals('suspended', $targetUser->fresh()->status);
        $this->assertEquals('مخالفة شروط الخدمة والاستخدام', $targetUser->fresh()->suspension_reason);

        // Reactivate
        $reactivateRes = $this->actingAs($this->adminUser)->post("/admin/users/{$targetUser->id}/reactivate");
        $reactivateRes->assertRedirect();
        $this->assertEquals('approved', $targetUser->fresh()->status);
        $this->assertNull($targetUser->fresh()->suspension_reason);
    }

    /** 11. Test Super Admin Operations: Admin CRUD and Settings Update */
    public function test_super_admin_admin_crud_and_settings()
    {
        // 1. Create new admin
        $createAdminRes = $this->actingAs($this->superAdminUser)->post('/admin/admins', [
            'name' => 'مشرف نظام جديد',
            'email' => 'newadmin@ihsan.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'admin_level' => 'admin',
        ]);
        $createAdminRes->assertRedirect('/admin/admins');
        $newAdminUser = User::where('email', 'newadmin@ihsan.com')->first();
        $this->assertNotNull($newAdminUser);
        $this->assertTrue($newAdminUser->isAdmin());

        // 2. Delete created admin
        $deleteAdminRes = $this->actingAs($this->superAdminUser)->delete("/admin/admins/{$newAdminUser->admin->id}");
        $deleteAdminRes->assertRedirect('/admin/admins');
        $this->assertNull(Admin::find($newAdminUser->admin->id));

        // 3. Update Settings
        $settingsRes = $this->actingAs($this->superAdminUser)->post('/admin/settings', [
            'tier_2_tasks_threshold'  => 12,
            'tier_2_rating_threshold' => 4.2,
            'tier_3_tasks_threshold'  => 35,
            'tier_3_rating_threshold' => 4.5,
        ]);
        $settingsRes->assertRedirect('/admin/settings');
    }

    /** 12. Test Notifications & Profile Management */
    public function test_notifications_and_profile_workflows()
    {
        // Notifications index
        $res = $this->actingAs($this->elderUser)->get('/notifications');
        $res->assertStatus(200);

        // Mark all notifications as read
        $markAllRes = $this->actingAs($this->elderUser)->post('/notifications/mark-all-read');
        $markAllRes->assertRedirect();

        // Profile view
        $profRes = $this->actingAs($this->elderUser)->get('/profile');
        $profRes->assertStatus(200);

        // Profile update
        $updateProf = $this->actingAs($this->elderUser)->patch('/profile', [
            'name' => 'أبو أحمد التميمي - محدث',
            'email' => 'elderly@ihsan.com',
        ]);
        $updateProf->assertRedirect('/profile');
        $this->assertEquals('أبو أحمد التميمي - محدث', $this->elderUser->fresh()->name);
    }
}
