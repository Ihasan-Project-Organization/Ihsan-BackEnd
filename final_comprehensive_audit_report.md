# 📑 التقرير الشامل النهائي لتدقيق منصة إحسان (Codebase Audit Report)

> **تاريخ الفحص والتدقيق:** 11 سبتمبر 2026  
> **طبيعة التقرير:** تقرير تدقيق وفحص برمجي وأمني شامل (Audit-Only) وفق معايير المراجعة الصارمة (Senior Staff Engineer Review)، دون إجراء أي تعديل على الكود.  
> **المرجع المعتمد للتحقق:** [المرجع الشامل للمشروع.md](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/المرجع_الشامل_للمشروع.md) ومستودع الكود الفعلي.

---

## 🧭 فهرس المحاور الأربعة

1. [المحور 1: الجودة البرمجية (Code Quality)](#المحور-1-الجودة-البرمجية-code-quality)
2. [المحور 2: الصحة المنطقية (Business Logic Correctness)](#المحور-2-الصحة-المنطقية-business-logic-correctness)
3. [المحور 3: التصميم وتجربة المستخدم (Design & UX)](#المحور-3-التصميم-وتجربة-المستخدم-design--ux)
4. [المحور 4: الأمان (Security) — الأكثر أهمية](#المحور-4-الأمان-security--الأكثر-أهمية)
5. [🚨 لائحة الأولويات للإصلاح (حسب درجة الخطورة)](#-لائحة-الأولويات-للإصلاح-مرتبة-من-الأخطر-للأقل-خطورة)

---

## المحور 1: الجودة البرمجية (Code Quality)

### 1. جدول رصد وفحص جودة الكود البرمجي

| الملف / الموضوع | الحالة | التفاصيل الدقيقة ومواقع الأسطر |
| :--- | :---: | :--- |
| **الدَّين التقني: استمرار نموذج التوافق `ServiceReview`** | ⚠️ يحتاج تحسين | لا يزال الملف [`app/Models/ServiceReview.php`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceReview.php#L8) موجوداً كوراثة فارغة `class ServiceReview extends Rating`. كما لا يزال هناك استيراد غير مستخدم له في [`app/Http/Controllers/ServiceRequestController.php:7`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ServiceRequestController.php#L7). |
| **الدَّين التقني: علاقات `HasManyThrough` في نموذج المستخدم** | ⚠️ يحتاج تحسين | في [`app/Models/User.php:147-158`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/User.php#L147-L158)، لا تزال دالتا `serviceRequests()` و `assignedServiceRequests()` تعتمدان على `hasManyThrough` للربط بين المستخدم والطلبات عبر جدول البروفايل بدلاً من التوحيد المباشر. |
| **الدَّين التقني: مسميات المفاتيح القديمة في علاقات التقييم** | ⚠️ يحتاج تحسين | في [`app/Models/User.php:163-174`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/User.php#L163-L174)، دالتا `givenReviews()` و `receivedReviews()` تستخدمان المفاتيح الأجنبية القديمة `elderly_id` و `provider_id` مع النموذج `Rating`. وفي [`app/Models/ServiceRequest.php:171`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceRequest.php#L171) لا تزال توجد علاقة `review()` كاسم مستعار لعلاقة التقييم المفرد. |
| **استعلامات N+1 في شاشة طلباتي لكبير السن** | 🔴 مشكلة حقيقية | في [`app/Http/Controllers/ServiceRequestController.php:30-32`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ServiceRequestController.php#L30-L32)، يتم تحميل `with(['assignedProvider', 'review'])`. علاقة `assignedProvider` تُرجع `ServiceProviderProfile`. وعند عرض البطاقات في [`resources/views/service-requests/index.blade.php:701-729`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/service-requests/index.blade.php#L701-L729)، يتم استدعاء `$item->assignedProvider->name`، مما يُشغّل الـ Accessor في [`app/Models/ServiceRequest.php:135`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceRequest.php#L135) الذي يستعلم عن `$this->serviceProviderProfile?->user`. هذا يُولّد استعلام `SELECT * FROM users WHERE id = ?` لكل بطاقة طلب في الحلقة (Lazy Loading). |
| **استعلامات N+1 في شاشات الإدارة** | ✅ سليم | تم فحص [`AdminRequestsController.php:16`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Admin/AdminRequestsController.php#L16)، و[`AdminUsersController.php:19`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Admin/AdminUsersController.php#L19)، و[`AdminComplaintsController.php:20,35`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Admin/AdminComplaintsController.php#L20)، و[`AdminAuditLogsController.php:18`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Admin/AdminAuditLogsController.php#L18)؛ جميعها تستخدم التحميل المسبق العميق بدقة (`with(['elderProfile.user', 'serviceProviderProfile.user'])` وغيرها) ولا تعاني من استعلامات متكررة. |
| **التكرار البرمجي (DRY): فحص صلاحيات الإلغاء وحجب الهاتف** | ✅ سليم | منطق الفحص الحرج مركزي 100% داخل الموديل: [`canBeCancelledByElderly()`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceRequest.php#L349) و [`canRevealContactPhone()`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceRequest.php#L321). يُستدعى فقط في الكنترولر وواجهات العرض دون أي تكرار يدوي لقائمة الحالات في أي مكان آخر. |
| **معالجة العمليات الحساسة والمعاملات (DB Transactions)** | 🔴 مشكلة حقيقية | في [`app/Http/Controllers/Admin/AdminAdminsController.php:63-86`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Admin/AdminAdminsController.php#L63-L86)، دالة `store()` تنشئ `User` ثم `Admin` ثم تدون في `AdminAuditLog` بدون `DB::transaction()` أو `try/catch`. إذا فشل إنشاء سجل الإدارة، يتبقى حساب مستخدم يتيم مفعل في قاعدة البيانات. وبالمثل دالة `destroy()` في السطور 107-111 تحذف دون معاملة ذرية. |
| **تنظيف الملفات عند فشل المعاملات (File Upload Transactions)** | ⚠️ يحتاج تحسين | في [`app/Http/Controllers/Auth/RegisteredUserController.php:65-66, 113-118`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Auth/RegisteredUserController.php#L65-L66)، يتم رفع الملفات وحفظها في القرص داخل كتلة `DB::transaction`. في حال حدوث خطأ في قاعدة البيانات، يتم التراجع عن السجلات ولكن الملفات المرفوعة تبقى يتيمة (Orphaned Files) على القرص دون آلية حذف تلقائي. |
| **تعليقات TODO المتبقية في المشروع** | ⚠️ يحتاج تحسين | وُجدت 5 تعليقات `// TODO` معلقة لم تُحذف: في الكنترولر [`VolunteerTaskController.php:423`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/VolunteerTaskController.php#L423)، و [`ProfileController.php:21, 71`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ProfileController.php#L21)، وملفات الاختبار [`ProfileTest.php:34`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/tests/Feature/ProfileTest.php#L34) و [`RegistrationTest.php:34`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/tests/Feature/Auth/RegistrationTest.php#L34). |
| **اتساق التسمية (Naming Conventions)** | ⚠️ يحتاج تحسين | الدوال تتبع camelCase والجداول تتبع snake_case بشكل ممتاز عامة، ولكن يوجد عدم اتساق في أسماء المفاتيح: جدول `ratings` يستخدم `elderly_id` بينما جدول `requests` يستخدم `elder_id`. بالإضافة إلى تضارب مسمى Accessor مع اسم علاقة في [`ServiceRequest.php:135,143`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceRequest.php#L135) بين `getAssignedProviderAttribute()` (تعيد User) و `assignedProvider()` (تعيد ServiceProviderProfile). |
| **ملفات ومسارات ميتة (Dead Code)** | ⚠️ يحتاج تحسين | المسار `POST /requests/{serviceRequest}/reviews` والدالة [`ServiceRequestController::storeReview`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ServiceRequestController.php#L392) كود ميت لم يعد يُستدعى مطلقاً، لأن التقييم أدمج إجبارياً ضمن `confirmCompletion()`. بالإضافة إلى مسارات التحويل القديمة غير المستخدمة في [`RegistrationPageController.php:26-34`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Frontend/RegistrationPageController.php#L26-L34) (`housing`, `documents`, `review`). |

---

## المحور 2: الصحة المنطقية (Business Logic Correctness)

تمت مطابقة الكود المصدري الفعلي مع كل بند وقرار في **[المرجع الشامل للمشروع.md](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/المرجع_الشامل_للمشروع.md)**:

### 1. حالات الطلب الـ11 وإمكانية الوصول إليها (State Machine Reachability)
* قائمة الحالات المعتمدة: `pending_acceptance`, `accepted`, `assigned`, `in_progress`, `pending_confirmation`, `completed`, `under_review`, `no_provider_found`, `provider_apologized`, `provider_delayed`, `cancelled`.
* **الفحص الميداني:**
  1. `pending_acceptance`: يمكن الوصول إليها عبر إنشاء طلب جديد أو إعادة البحث عن متطوع بديل.
  2. `accepted`: يمكن الوصول إليها عند قبول المتطوع للطلب في [`VolunteerTaskController::accept:361`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/VolunteerTaskController.php#L361).
  3. **`assigned` (حالة ميتة في المسار الطبيعي 🔴):** في الكود الحالي، دالة القبول `accept()` تنقل الطلب فوراً إلى حالة `accepted` وتضع معاً `accepted_at` و `assigned_at`. ومن ثم عند بدء الخدمة ينقلها المتطوع مباشرة إلى `in_progress`. **لا يوجد أي مسار تشغيلي طبيعي للمستخدم ينقل الطلب إلى حالة `assigned`**. بقيت هذه الحالة في الكود كمجرد فحص موازٍ لـ `accepted` في الواجهات، أو عبر تغيير الحالة الإداري القسري (`forceStatus`)!
  4. `in_progress`: يمكن الوصول إليها عبر بدء الخدمة [`VolunteerTaskController::startService:388`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/VolunteerTaskController.php#L388).
  5. `pending_confirmation`: يمكن الوصول إليها عبر إنهاء الخدمة من المتطوع [`VolunteerTaskController::finishService:404`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/VolunteerTaskController.php#L404).
  6. `completed`: يمكن الوصول إليها عند تأكيد كبير السن وتقييم الخدمة [`ServiceRequestController::confirmCompletion:236`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ServiceRequestController.php#L236).
  7. `provider_apologized`: يمكن الوصول إليها عبر اعتذار المتطوع [`VolunteerTaskController::apologize:454`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/VolunteerTaskController.php#L454).
  8. `provider_delayed`: يمكن الوصول إليها يدوياً عبر إبلاغ المتطوع عن تأخير، أو آلياً عبر الجدولة.
  9. `no_provider_found`: يمكن الوصول إليها آلياً عبر الجدولة الزمنية عند حلول الموعد بلا قبول.
  10. `under_review`: يمكن الوصول إليها عندما يُبلغ كبير السن عن مشكلة أو اعتراض [`ServiceRequestController::reportProblem:299`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ServiceRequestController.php#L299).
  11. `cancelled`: يمكن الوصول إليها عبر إلغاء كبير السن للطلب في الحالات المسموحة [`ServiceRequestController::cancel:380`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ServiceRequestController.php#L380).

### 2. سياسة الإلغاء وحماية الحالات المحظورة الثلاث (Cancellation Policy)
* **القرار المعتمد:** منع الإلغاء تماماً في الحالات: `assigned`, `in_progress`, `pending_confirmation`.
* **التحقق من الكود:**
  - في الموديل: دالة [`canBeCancelledByElderly()`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceRequest.php#L349) تحصر الإلغاء في `pending_acceptance`, `accepted`, `no_provider_found`, `provider_apologized`, `provider_delayed`. وتستبعد الحالات المحظورة استبعاداً تاماً.
  - في الواجهة الخلفية: دالة [`ServiceRequestController::cancel:371`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ServiceRequestController.php#L371) تفحص:
    ```php
    if (! $serviceRequest->canBeCancelledByElderly()) {
        return back()->withErrors(['status' => 'لا يمكن إلغاء الطلب في حالته الحالية.']);
    }
    ```
  - لا يوجد أي مسار آخر يمكن لكبير السن من خلاله تعديل حالة الطلب إلى ملغي دون المرور بهذا الفحص. ✅ **سليم ومحمي بالكامل.**

### 3. التقييم الإجباري لإغلاق الطلب (Mandatory Rating)
* **القرار المعتمد:** لا يمكن إغلاق الطلب وتحويله إلى `completed` إلا بتقييم إجباري من 1 إلى 5 نجوم لكبير السن.
* **التحقق من الكود:**
  - في [`ServiceRequestController::confirmCompletion:227-230`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ServiceRequestController.php#L227-L230):
    ```php
    $stars = (int) ($validated['stars'] ?? $validated['rating'] ?? 0);
    if ($stars < 1 || $stars > 5) {
        return back()->withErrors(['rating' => 'يرجى اختيار تقييم بالنجوم (1-5) لتأكيد اكتمال الخدمة.']);
    }
    ```
  - التحديث إلى `status = completed` وإنشاء سجل `Rating` يتمان معاً داخل `DB::transaction` في السطور 232-274.
  - لا توجد أي ثغرة أو مسار يمكن من خلاله لكبير السن تحويل الطلب لـ `completed` دون تقييم. ✅ **سليم ومطبق بدقة.**

### 4. قفل التزامن عند قبول الطلب (Race Condition & Concurrency Locking)
* **القرار المعتمد:** أول من يوافق يفوز ويُقفل الطلب فوراً لمنع تعيين متطوعين اثنين لنفس الطلب.
* **التحقق من الكود:**
  - في [`VolunteerTaskController::accept:349-368`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/VolunteerTaskController.php#L349-L368):
    ```php
    return DB::transaction(function () use ($serviceRequest, $providerProfile) {
        $requestLocked = ServiceRequest::where('id', $serviceRequest->id)
            ->lockForUpdate()
            ->first();

        if ($requestLocked->status !== ServiceRequest::STATUS_PENDING_ACCEPTANCE || $requestLocked->provider_id !== null) {
            return redirect()->route('provider.available')
                ->with('error', 'أُسند لغيرك: لقد قام مقدم خدمة آخر بقبول هذا الطلب أولاً.');
        }

        $requestLocked->update([...]);
    });
    ```
  - **التقييم الفني:** منطقياً، الكود يستخدم المعاملة الذرية وقفل القراءة الحصري لصفوف قاعدة البيانات (`lockForUpdate()`) في محرك InnoDB.
  - **تنبيه القيود الصارمة:** بما أن محاكاة مئات الطلبات المتزامنة اللحظية (High Concurrency / Stress Testing) تتطلب بيئة اختبار حمل متعددة الخيوط مخصصة، فإن هذا البند: **"يحتاج اختبارًا يدويًا إضافيًا تحت ضغط طلبات متزامنة (Stress Test / Concurrency Benchmark)"** للتحقق من أداء قفل السجلات تحت أزمنة استجابة متناهية الصغر.

### 5. معادلة المستويات (Tier) وعدّاد الموثوقية
* **القرار المعتمد:** قراءة عتبات الترقية ديناميكياً من جدول `system_settings` بدلاً من القيم الثابتة.
* **التحقق من الكود:**
  - في [`ServiceProviderProfile::updateTier():96-99`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceProviderProfile.php#L96-L99):
    ```php
    $tier3Tasks = (int) SystemSetting::get('tier_3_tasks_threshold', 30);
    $tier3Rating = (float) SystemSetting::get('tier_3_rating_threshold', 4.3);
    $tier2Tasks = (int) SystemSetting::get('tier_2_tasks_threshold', 10);
    $tier2Rating = (float) SystemSetting::get('tier_2_rating_threshold', 4.0);
    ```
    ✅ **تُقرأ ديناميكياً بالكامل** من إعدادات النظام مع قيم افتراضية احتياطية.
  - **ملاحظة تحسين (⚠️):** فحص عتبة حوادث الموثوقية في [`ServiceProviderProfile::checkReliabilityAlert():156`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceProviderProfile.php#L156) لا يزال يعتمد على رقم ثابت في الكود (`$recentCount >= 3`) بدلاً من قراءته من `SystemSetting::get('reliability_incidents_threshold', 3)`.

### 6. الجدولة التلقائية لمعالجة انتهاء المواعيد (Scheduler)
* **القرار المعتمد:** تشغيل `processScheduleExpirations()` دورياً.
* **التحقق من الكود:**
  - في [`routes/console.php:10-17`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/routes/console.php#L10-L17): أمر الأرتيزان `requests:check-expirations` مسجل ومجدول ليعمل كل دقيقة:
    ```php
    \Illuminate\Support\Facades\Schedule::command('requests:check-expirations')->everyMinute();
    ```
  - الدالة منفذة بالكامل في [`ServiceRequest::processScheduleExpirations()`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceRequest.php#L365)، بل ويتم استدعاؤها احترازياً عند تحميل شاشة الطلبات لكبير السن في [`ServiceRequestController.php:23`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ServiceRequestController.php#L23) لضمان تحديث فوري للحالات. ✅ **سليم ويعمل فعلياً.**

### 7. فصل الأدوار وحماية مسارات المستخدمين (Role Isolation)
* **التحقق من الكود:**
  - تم فحص وسطاء الحماية (Middlewares) في [`bootstrap/app.php:14-19`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/bootstrap/app.php#L14-L19):
    - `EnsureUserRole` محددة بـ `role:elder` و `role:provider`.
    - `EnsureAdmin` محددة بـ `ensure.admin`.
    - `EnsureSuperAdmin` محددة بـ `ensure.super`.
  - كل مجموعة مسارات في [`routes/web.php`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/routes/web.php) مغلفة بالوسيط المقابل وتمنع التداخل برمز خطأ 403 صريح عند اختلاف الدور. ✅ **سليم ومحمي.**

---

## المحور 3: التصميم وتجربة المستخدم (Design & UX)

### 1. اتساق الهوية البصرية (Visual Identity Harmony)
* **لوحة الألوان الموحدة:** تلتزم البوابات الثلاث (كبير السن، مقدم الخدمة، الإدارة) بنفس اللوحة اللونية المعتمدة:
  - اللون الزيتوني الأساسي: `#31421e` / `#354e20`
  - اللون العشبي الثانوي: `#718256` / `#4e6b35` / `#83a55b`
  - خلفيات الرمال الدافئة: `#f2ede4` / `#f8f6f0`
  - عناصر الدعم والبطاقات: `#ffffff` مع حواف ناعمة بقطر كبير (Border Radius: 14px - 24px).
* **الخطوط:** استخدام خط `Alexandria` الموحد عبر استدعاء Google Fonts في جميع الـ Layouts.
* **الأنماط:** أزرار موحدة بحواف نصف دائرية (Pill-shaped) أو زوايا مدورة 12px-16px مع حركات التفاعل (Hover transitions).

### 2. الاستجابة للشاشات الصغيرة (Mobile Responsiveness)
* **الشاشات العامة وشاشات كبير السن والمتطوع:** متوافقة تماماً وتستخدم فئات Tailwind المتجاوبة (`grid-cols-1 sm:grid-cols-2 lg:grid-cols-4`).
* **شاشات الإدارة (⚠️ يحتاج تحسين):**
  - شريط التصفح الجانبي (Sidebar) متجاوب ويحتوي على زر القائمة المنسدلة للجوال وخلفية ضبابية (Backdrop) في [`layouts/admin.blade.php:293-300`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/layouts/admin.blade.php#L293-L300).
  - **نقطة ضعف متجاوبة:** في صفحات التفاصيل الخاصة بالإدارة:
    - [`admin/approvals/show.blade.php:37, 144, 242`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/admin/approvals/show.blade.php#L37)
    - [`admin/requests/show.blade.php:27`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/admin/requests/show.blade.php#L27)
    - [`admin/complaints/show.blade.php:26`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/admin/complaints/show.blade.php#L26)
    - [`admin/users/show.blade.php:49`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/admin/users/show.blade.php#L49)
    استُخدمت أنماط مدمجة صريحة مثل `style="display:grid; grid-template-columns: 340px 1fr;"` أو `grid-template-columns: 1fr 1fr;` دون استعلام وسائط (`@media`). هذا يؤدي على شاشات الهواتف الضيقة (أقل من 600px) إلى ضغط غير مناسب للأعمدة وحدوث تمرير أفقي غير مريح.

### 3. صحة اتجاه RTL (RTL Layout Integrity)
* **الهيكل العام:** جميع الصفحات موسومة بـ `<html lang="ar" dir="rtl">`.
* **محاذاة الأرقام والهواتف:** الأرقام الهاتفية والهويات تلتزم بتحديد الاتجاه المعاكس `dir="ltr"` لمنع تشوه الأرقام والبادئات الدولية في واجهات المودال وبطاقات التفاصيل (مثل [`action-modals.blade.php:314`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/service-requests/partials/action-modals.blade.php#L314) و [`approvals/show.blade.php:68`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/admin/approvals/show.blade.php#L68)).
* **أيقونات الأسهم والتنقل:** أيقونات الرجوع تلتزم بالسهم المتجه يميناً في الواجهات العربية مثل `fa-arrow-right` في أزرار الرجوع للقوائم السابقة.

### 4. الحالات الفارغة (Empty States)
* جميع الشاشات التي تحوي حلقات تكرارية مزودة بحالات فارغة مصممة بعناية:
  - قائمة الطلبات لكبير السن: [`service-requests/index.blade.php:1006`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/service-requests/index.blade.php#L1006).
  - قائمة المهام المتاحة للمتطوع: [`provider/available.blade.php:150-166`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/provider/available.blade.php#L150-L166).
  - شاشات الإدارة (المستخدمون، الطلبات، الشكاوى، الاعتمادات، سجل العمليات): جميعها تستخدم `@empty` مع أيقونة معبرة ورسالة توجيهية واضحة وممتدة على كامل عرض الجدول (`colspan`).

### 5. حالات التحميل والأخطاء (Loading & Feedback States)
* **الملاحظات والتنبيهات (Flash Messages):** تظهر رسائل النجاح والتحذير في بطاقات ملونة مميزة في أعلى النماذج (`session('success')`, `session('error')`).
* **نقاط ضعف في تجربة المستخدم (⚠️ يحتاج تحسين):**
  1. في مودال إنشاء الطلب لكبير السن [`create-modal.blade.php:39-57`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/service-requests/partials/create-modal.blade.php#L39-L57)، يُستخدم `alert(...)` الافتراضي للمتصفح لإظهار أخطاء التحقق بين الخطوات بدلاً من إبراز حقول الإدخال غير المكتملة وتنبيهات داخلية ضمن الواجهة.
  2. لا توجد مؤشرات تحميل تفاعلية (Loading Spinners) أو تعطيل فوري للزر عند ضغط الإرسال في المودالات (مثل `finishServiceForm`, `reportDelayForm`, `apologizeForm`)، مما يسمح بنقرات مزدوجة متكررة سريعة (Double Submission) قبل استجابة الخادم.

---

## المحور 4: الأمان (Security) — الأكثر أهمية

### 1. الحسابات التجريبية وأزرار التعبئة السريعة (🔴 مشكلة أمنية حقيقية)
* **الملف المتأثر:** [`resources/views/frontend/login.blade.php`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/frontend/login.blade.php#L340-L417)
* **رقم السطر:** الأسطر من 340 إلى 417، ودالة JS في الأسطر 459-481.
* **تفاصيل الثغرة:** تم تضمين كتلة أزرار التعبئة السريعة لحسابات النظام (Super Admin, Admin, Provider, Elder, Suspended, Pending) مع كلمات المرور المباشرة `'password'` داخل الكود المصدري لصفحة تسجيل الدخول **دون أي قيد لبيئة العمل** (غياب تام لشرط `@if(app()->environment('local'))`).
* **الخطورة:** في حال رفع هذا الكود على بيئة استضافة حقيقية أو خادم إنتاج، ستظهر بيانات الحسابات الإدارية وحسابات كبار السن والمتطوعين أمام أي زائر بضغطة زر واحدة لتسجيل الدخول فوراً!

### 2. حماية رفع الملفات وتخزين المستندات الرسمية (🔴 مشكلة أمنية حقيقية)
* **الملفات المتأثرة:** 
  - [`app/Http/Controllers/Auth/RegisteredUserController.php:65, 66, 118`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Auth/RegisteredUserController.php#L65)
  - [`resources/views/admin/approvals/show.blade.php:154, 168, 175, 197, 211`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/admin/approvals/show.blade.php#L154)
* **تفاصيل الثغرة:**
  - **التحقق من الملفات (سليم جزئياً):** يتم التحقق من الحجم الأقصى (5120KB) والنوع عبر `mimes:png,jpg,jpeg,pdf` التي تفحص محتوى الـ MIME وليس الامتداد فقط.
  - **التخزين والوصول (ثغرة خطيرة):** يتم تخزين صور الهويات الوطنية الشخصية وشهادات حسن السيرة والسلوك الجنائية على القرص العام:
    ```php
    $idDocPath = $request->file('id_document')->store('documents/ids', 'public');
    $conductCertPath = $request->file('conduct_document')->store('documents/certificates', 'public');
    ```
    ويتم عرضها وتحميلها في لوحة الإدارة عبر رابط الويب العام المباشر:
    ```blade
    <a href="{{ asset('storage/' . $idDocPath) }}" target="_blank">
    ```
* **الخطورة:** هذه المستندات تعتبر بيانات شخصية حساسة للغاية (PII). وجودها في مجلد `storage/app/public` يجعلها متاحة للعموم دون الحاجة لتسجيل الدخول بمجرد معرفة المسار أو تخمينه، دون المرور بأي وسيط أمني للمصادقة أو تفويض الإدارة. يجب حفظها في القرص الخاص `local` وتخديمها عبر Controller محمي بالصلاحيات الإدارية (`Storage::disk('local')->response(...)`).

### 3. تفويض Backend في قبول المهمة مقابل الفلترة البصرية (🔴 ثغرة تجاوز الصلاحيات)
* **الملف المتأثر:** [`app/Http/Controllers/VolunteerTaskController.php`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/VolunteerTaskController.php#L337-L368)
* **رقم السطر:** دالة `accept()`، الأسطر 337 إلى 368.
* **تفاصيل الثغرة:** في صفحة استعراض المهام المتاحة (`available`)، تتم فلترة المهام بصرياً وفق تفضيل الجنس ومستوى المتطوع `ServiceRequest::availableForProvider($provider)`. ولكن عند إرسال طلب القبول `POST /provider/tasks/{serviceRequest}/accept`، **لا تقوم دالة `accept()` بإعادة التحقق من أن هذا الطلب يطابق شروط هذا المتطوع بالذات** (لا تفحص مستوى الـ Tier أو تفضيل الجنس).
* **الخطورة:** يمكن لأي متطوع مسجل ومصرح له (حتى لو كان Tier 1 أو بجنس غير مطابق) إرسال طلب POST مباشر لمعرف طلب مخصص لـ Tier 3 أو بتفضيل جنس معين، وسيقوم النظام بتسجيله وقبول المهمة بنجاح، مما يمثل اعتماداً غير آمن على الإخفاء البصري في الواجهة الأمامية دون تأكيد في الخلفية.

### 4. التحصين ضد التعيين الجماعي (Mass Assignment Protection)
* **التحقق من الكود:**
  - تم فحص جميع النماذج الـ 14 في [`app/Models/`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models):
    `User`, `ServiceRequest`, `Rating`, `ServiceProviderProfile`, `ElderProfile`, `Admin`, `AdminAuditLog`, `Complaint`, `Notification`, `ProviderReliabilityIncident`, `RequestAttachment`, `SystemSetting`, `VolunteerCertificate`.
  - **النتيجة:** لا يوجد أي نموذج على الإطلاق يستخدم `$guarded = []`. جميع النماذج بلا استثناء تُعرف `protected $fillable = [...]` بحقول محددة ومحصورة بدقة. ✅ **سليم ومحصن.**

### 5. الحماية ضد القوة الغاشمة (Brute Force Rate Limiting)
* **التحقق من الكود:**
  - مسار `/login` يُعالج عبر [`app/Http/Controllers/Auth/AuthenticatedSessionController.php:25`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Auth/AuthenticatedSessionController.php#L25) مستخدماً [`app/Http/Requests/Auth/LoginRequest.php`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Requests/Auth/LoginRequest.php).
  - دالة `ensureIsNotRateLimited()` في السطور 78-94 تستدعي:
    ```php
    RateLimiter::tooManyAttempts($this->throttleKey(), 5)
    ```
  - يتم القفل والحظر المؤقت فور تجاوز 5 محاولات فاشلة بالاعتماد على مفتاح مدمج من البريد وعنوان الـ IP (`$email . '|' . $request->ip()`). ✅ **سليم ومحمي.**

### 6. حماية معلومات النظام والأخطاء (Leakage of Sensitive Data & APP_DEBUG)
* **التحقق من الكود:**
  - ملف [`.env`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/.env) يحتوي حالياً على `APP_DEBUG=true` و `APP_ENV=local`.
  - لا توجد أي مفاتيح تشفير أو رموز سرية لواجهات برمجة خارجية مسربة داخل ملفات Blade أو Javascript.
  - **تنبيه:** يجب إلزامياً ضبط `APP_DEBUG=false` عند الانتقال إلى أي بيئة اختبار نهائية أو إنتاج لمنع ظهور رسائل الـ Stack Traces ومسارات الخادم عند حدوث أخطاء غير متوقعة.

### 7. الحماية ضد ثغرات CSRF و XSS
* **فحص CSRF:** تم فحص كافة نماذج الإرسال (POST, PATCH, DELETE) في كامل مجلد `resources/views` (بما فيها لوحات الإدارة الأحدث والمودالات المنبثقة). **جميعها بنسبة 100% تحتوي على وسم `@csrf`**.
* **فحص XSS:** تم البحث في كامل المشروع عن وسوم الطباعة غير المشفرة `{!! !!}`. النتيجة: **صفر استخدامات غير آمنة**. كافة بيانات المستخدمين والمدخلات النصية تُعرض عبر التشفير التلقائي لـ Blade باستخدام `{{ $variable }}`. ✅ **سليم ومحصن.**

### 8. حماية الحذف الذاتي للمدير الأعلى (Super Admin Self-Deletion Protection)
* **التحقق من الكود:**
  - في [`app/Http/Controllers/Admin/AdminAdminsController.php:97-100`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Admin/AdminAdminsController.php#L97-L100):
    ```php
    // منع المدير الحالي من حذف نفسه
    if ($admin->user_id === Auth::id()) {
        return back()->with('error', 'لا يمكنك حذف حسابك الخاص كمدير لنظام إحسان.');
    }
    ```
  - وفي [`app/Http/Controllers/Admin/AdminUsersController.php:124-126`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Admin/AdminUsersController.php#L124-L126):
    ```php
    if ($user->id === auth()->id()) {
        return back()->with('error', 'لا يمكنك إيقاف حسابك الإداري الخاص.');
    }
    ```
  - التحقق مطبق برمجياً في الـ Backend ولا يعتمد فقط على إخفاء الزر في الواجهة. ✅ **سليم ومحمي.**

---

## 🚨 لائحة الأولويات للإصلاح (مرتبة من الأخطر للأقل خطورة)

هذه القائمة مخصصة ومجهزة مباشرة لبرومبت الإصلاح القادم، وتقتصر على كل بند يحمل علامة 🔴 (مشكلة حقيقية):

| الأولوية | البند ومستوى الخطورة | الملف والأسطر المتأثرة | ملخص الإصلاح المطلوب بالبرومبت القادم |
| :---: | :--- | :--- | :--- |
| **1** | **🔴 حرجة للغاية:** كشف الحسابات التجريبية وكلمات المرور في صفحة تسجيل الدخول | [`resources/views/frontend/login.blade.php:340-417`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/frontend/login.blade.php#L340-L417) | تغليف كتلة الحسابات التجريبية بالكامل بشرط بيئة العمل المحلية: `@if(app()->environment('local')) ... @endif` بحيث لا تظهر إطلاقاً في بيئة الإنتاج. |
| **2** | **🔴 حرجة جداً:** تخزين مستندات الهوية وشهادات السيرة في القرص العام وإتاحتها بروابط عامة | [`RegisteredUserController.php:65,66,118`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Auth/RegisteredUserController.php#L65)<br>[`AdminApprovalsController.php`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Admin/AdminApprovalsController.php)<br>[`admin/approvals/show.blade.php`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/resources/views/admin/approvals/show.blade.php) | نقل حفظ ملفات الهوية والشهادات إلى قرص محمي `local` خاص (غير عام)، واستبدال روابط العرض العامة `asset('storage/...')` بمسار مخصص ومحمي بالصلاحيات الإدارية يعرض الملف عبر `Storage::response()`. |
| **3** | **🔴 عالية:** تجاوز صلاحيات قبول المهمة دون التحقق من مطابقة المستوى والجنس في الـ Backend | [`app/Http/Controllers/VolunteerTaskController.php:337-368`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/VolunteerTaskController.php#L337-L368) | إضافة فحص إلزامي داخل دالة `accept()` للتحقق من أن الطلب يطابق مؤهلات ومستوى المتطوع وتفضيل الجنس، وعدم الاعتماد على مجرد إخفائه في قائمة العرض. |
| **4** | **🔴 متوسطة - أمان واستقرار بيانات:** غياب المعاملات الذرية `DB::transaction()` عند إنشاء وحذف المديرين | [`app/Http/Controllers/Admin/AdminAdminsController.php:44-90, 95-126`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/Admin/AdminAdminsController.php#L44) | تغليف عمليات دالتي `store()` و `destroy()` داخل `DB::transaction()` لمنع تولد سجلات مستخدمين مفعلة يتيمة عند حدوث أي خطأ في إنشاء سجل الإدارة أو التدقيق. |
| **5** | **🔴 متوسطة - صحة معمارية:** استعلام N+1 غير مقصود في شاشة طلباتي لكبير السن | [`ServiceRequestController.php:30-32`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/ServiceRequestController.php#L30)<br>[`ServiceRequest.php:135`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceRequest.php#L135) | استبدال `with(['assignedProvider', 'review'])` بـ `with(['serviceProviderProfile.user', 'review'])` وضبط التوافقية لمنع تكرار استعلام جدول `users` لكل بطاقة طلب. |
| **6** | **🔴 متوسطة - دقة منطقية:** خروج الحالة المعتمدة `assigned` من دورة حياة الطلب الواقعية | [`VolunteerTaskController.php:360`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Http/Controllers/VolunteerTaskController.php#L360)<br>[`ServiceRequest.php:26`](file:///c:/Users/pc/OneDrive/Desktop/BackEnd/Ihsan_Project/app/Models/ServiceRequest.php#L26) | مواءمة دورة الحياة: إما بتفعيل انتقال حقيقي لحالة `assigned` بدلاً من القفز من `accepted` مباشرة إلى `in_progress`، أو توحيد الاصطلاح وفق ما استقر عليه المرجع الشامل. |
