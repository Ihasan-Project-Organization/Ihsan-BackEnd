# تقرير التدقيق الشامل لمشروع منصة إحسان

تم إعداد هذا التقرير بناءً على الفحص الشامل والمقارنة المباشرة بين الكود الفعلي للمشروع وملف **المرجع الشامل للمشروع** (`المرجع_الشامل_للمشروع.md`)، دون إجراء أي تعديل أو تشغيل أي أمر على قاعدة البيانات.

---

## 1. تدقيق قاعدة البيانات

| الجدول/العمود | الحالة | ملاحظة |
|---|---|---|
| `users` (الأعمدة الأساسية: id, name, email, password, timestamps) | مطابق | متوافقة مع إعدادات Laravel الأساسية ومذكورة في المرجع. |
| `users.account_type` | يحتاج تعديل | موجود في migration (`2026_08_17_000000`) بقيم (`elderly`, `volunteer`). المرجع يعتمد 3 أدوار رئيسية مع الإدارة (Admin / Super Admin) وتوزيع الصلاحيات عبر الـ `role`. |
| `users.profile_picture_path` | مفقود | معتمد بالمرجع (§3 و §11) كصورة اختيارية لكل المستخدمين على جدول `users`، بينما وُجد حقل شبيه (`profile_photo_path`) بالخطأ على جدول `registration_profiles`. |
| `users.status` (حالة الاعتماد) | مفقود | المرجع (§3) يشترط خضوع الحسابات للاعتماد الإداري بحالات (`pending`, `approved`, `rejected`)، ولا يوجد أي عمود لحالة الحساب حالياً. |
| `elder_profiles` | مفقود | غير موجود في أي migration. المرجع (§11) يعتمده كجدول مستقل لبيانات كبير السن (المدينة، صورة إثبات الهوية). يُستخدم حالياً جدول `registration_profiles` الملغى بدلاً منه. |
| `service_provider_profiles` | مفقود | غير موجود في أي migration. المرجع (§6 و §11) يعتمده شاملاً (`tier`, `completed_tasks_count`, `average_rating`, `is_available`, `reliability_incidents_count`، وتاريخ الميلاد، وصورة الهوية، وحسن السيرة)، بدون حقول المدن أو النقاط. |
| `admins` | مفقود | غير موجود في أي migration. معتمد في المرجع (§2 و §10 و §11) لإدارة مستويي الإدارة (Admin و Super Admin). |
| اسم جدول `service_requests` | يحتاج تعديل | مسمى الجدول في المرجع (§11) هو `requests`، بينما تم إنشاؤه في الكود باسم `service_requests`. |
| `service_requests.status` | يحتاج تعديل | نوعه `string` ويحتوي حالياً في الكود على حالات ملغاة/زائدة (`on_the_way`, `arrived`, `no_show`) ويفتقر للحالة المعتمدة `assigned` (§4.3). |
| `service_requests.service_type` | يحتاج تعديل | موجود افتراضياً كـ `grocery`، بينما المرجع (§4.1) يحدد 5 أنواع معتمدة: (مرافقة، تسوق-لوجستية، مرافقة طبية، مساعدة منزلية خفيفة، دعم تقني). |
| `service_requests.pricing_type` و `proposed_price` | مفقود | المرجع (§4.1) يفرض تصنيف الخدمة (تطوعية أو مدفوعة + تحديد السعر المقترح إن كانت مدفوعة). غير موجودة كأعمدة في الطلب. |
| `service_requests.timing_type` | مفقود | المرجع (§4.1) يفرض تحديد التوقيت (فوري أو مجدول بتاريخ ووقت)، بينما الموجود حالياً عمود `scheduled_at` فقط. |
| `service_requests.gender_preference` | مفقود | المرجع (§4.1 و §4.2) يفرض تحديد تفضيل الجنس (ذكر / أنثى / لا يهم) للربط، وغير موجود بالجدول. |
| `service_requests.incident_type` | مفقود | المرجع (§6 و §11) يفرضه صراحة كـ `enum('apology', 'delay', 'no_show')` قابل لأن يكون `null` لتوثيق أي حادثة تشغيلية على مستوى الطلب. |
| `service_requests.assigned_at` | مفقود | مذكور نصاً في المرجع (§11) لحفظ تاريخ ووقت التوكيل الرسمي، بينما المتوفر فقط `accepted_at`. |
| `service_requests.attempts_count` | زائد ويجب حذفه | عمود مرتبط بنظام المحاولات القديم المنفصل، بينما إعادة النشر تتم على نفس الطلب مباشرة (§4.5). |
| `service_requests.district` و `distance_km` | زائد ويجب حذفه | أُضيفا في migration لاحق (`2026_09_02_000001`). الفلترة الجغرافية وحساب المسافات مؤجلة كلياً بالمرجع (§1 و §13). |
| `service_requests.on_the_way_at` و `arrived_at` | زائد ويجب حذفه | طوابع زمنية لحالات ملغاة (`on_the_way`, `arrived`) غير موجودة في دورة حياة الطلب (§4.3). |
| `service_requests.delay_reported_at` و `expected_arrival_at` و `delay_reason` | زائد ويجب حذفه | حقول مضافة لمسار التأخير القديم؛ بينما المرجع يكتفي بتغيير حالة الطلب لـ `provider_delayed` وتوثيق `incident_type = 'delay'`. |
| `service_requests.completion_notes` | زائد ويجب حذفه | ملاحظات إنهاء من المقدم؛ المرجع لا يشترطها حيث يُنقل الطلب مباشرة إلى `pending_confirmation`. |
| `request_attachments` | مفقود | معتمد بالمرجع (§4.1 و §11) لحفظ المرفقات الاختيارية (صور/ملفات) مع الطلب، ولا يوجد له جدول migration. |
| اسم جدول `service_reviews` | يحتاج تعديل | معتمد بالمرجع (§11) باسم `ratings`. |
| `service_reviews.rating` | يحتاج تعديل | المسمى في المرجع (§11) هو `stars` (نجوم من 1 إلى 5). |
| `service_reviews` (تقييم مقدم الخدمة لكبير السن) | يحتاج تعديل | الجدول الحالي يقبل فقط تقييم كبير السن للمقدم (`elderly_id` -> `provider_id`)، بينما المرجع (§5) يتيح تقييماً اختيارياً متبادلاً من المقدم يظهر للإدارة فقط. |
| `complaints` | مفقود | غير موجود في أي migration، وهو جدول معتمد (§7 و §11) لشكاوى كبار السن ومقدمي الخدمة. |
| `volunteer_certificates` | مفقود | غير موجود في أي migration، وهو جدول معتمد (§8 و §11) لإصدار شهادات التطوع الرقمية بالرقم التسلسلي الفريد. |
| `notifications` | مفقود | غير موجود في migrations، معتمد بالمرجع (§11 و §14) لإشعارات النظام والإدارة. |
| `registration_profiles` | زائد ويجب حذفه | جدول كامل (`2026_08_17_000001` و `2026_08_24_000000`) من نظام تسجيل قديم ملغى، والبديل المعتمد هو تفكيكه إلى `elder_profiles` و `service_provider_profiles`. |
| `request_attempts` | زائد ويجب حذفه | جدول كامل (`2026_08_25_000002`) لتعقب المحاولات، غير مذكور في المرجع الشامل؛ فالطلب يُعاد نشره بنفس المعرّف مباشرة (§4.5). |
| `provider_settings` | زائد ويجب حذفه | جدول كامل (`2026_09_02_000002`) يحتوي أعمدة أنظمة نقاط ملغاة صراحة (`commitment_score`, `punctuality_rate`, `completion_rate`, `response_rate`) وأعمدة جغرافية ملغاة (`service_city`, `coverage_radius_km`). حقول التوفر المعتمدة مكانها `service_provider_profiles`. |
| `provider_dismissed_requests` | زائد ويجب حذفه | جدول (`2026_09_02_000003`) لتجاوز الطلبات، غير معتمد في القسم 11 من المرجع الشامل. |

---

## 2. تدقيق منطق الأعمال

| الملف | الموضوع | الحالة | ملاحظة |
|---|---|---|---|
| `app/Http/Controllers/ServiceRequestController.php` | سياسة الإلغاء (القسم 4.4) | يحتاج تعديل (ثغرة منطقية) | دالة `cancel()` تنفّذ الإلغاء على أي طلب يملكه المستخدم مباشرة دون فحص حالته عبر `canBeCancelledByElderly()`. المسار مفتوح برمجياً لأي حالة حتى أثناء التنفيذ. |
| `resources/views/service-requests/index.blade.php` | ظهور زر الإلغاء (القسم 4.4) | يحتاج تعديل | زر الإلغاء مقيد بصرياً في أغلب الحالات، لكنه يظهر في `pending_acceptance` و `provider_apologized` و `provider_delayed`. ولكنه **مفقود تماماً** لحالة `no_provider_found` (الحالة بأكملها غير معالجة في الواجهة). |
| `app/Http/Controllers/ServiceRequestController.php` | إلزامية التقييم لإغلاق الطلب (القسم 4.8 و 5) | يحتاج تعديل | دالة `confirmCompletion()` تحول الطلب إلى `completed` مباشرة دون إرفاق تقييم، ودالة التقييم `storeReview()` منفصلة واختيارية لاحقاً، بينما المرجع يشترط أن التقييم إجباري لإغلاق الطلب كـ `completed`. |
| `resources/views/service-requests/partials/action-modals.blade.php` | نموذج التقييم (القسم 5) | مطابق جزئياً | النموذج في المودال بسيط (نجوم 1-5 + تعليق اختياري) وهو مطابق للقرار، لكنه غير مدمج مع إجراء التأكيد والإغلاق، ولا يوجد نموذج لتقييم المقدم للمستفيد. |
| `resources/views/provider/performance.blade.php` | معايير التقييم المتعددة في شاشة الأداء (القسم 5 و 14) | زائد ويجب حذفه | الشاشة تحتوي على 4 معايير تفصيلية ثابتة بالأسطر (30-47): "حسن التعامل"، "الالتزام بالموعد"، "جودة الخدمة"، "الأمان والراحة"، وهي معايير ملغاة صراحة بالمرجع. |
| `app/Http/Controllers/VolunteerTaskController.php` | حساب مؤشر الالتزام ومعادلات النقاط (القسم 6) | زائد ويجب حذفه بالكامل | دالة `reportDelay()` تخصم 2 أو 5 نقاط من `commitment_score`، ودالة `apologize()` تخصم 2 أو 5 أو 10 نقاط حسب وقت الاعتذار. هذا النظام ملغى صراحة بالمرجع الشامل (§6). |
| `resources/views/provider/performance.blade.php` | رسوم ونسب مؤشر الالتزام (القسم 6 و 14 شاشة 14) | زائد ويجب حذفه بالكامل | الجانب الأيسر من الشاشة (الأسطر 56-103) يعرض "مؤشر الالتزام" مع أشرطة تقدم لـ "الحضور بالموعد"، "إكمال الطلبات"، و"سرعة الاستجابة". المرجع يمنع أي رسم أو نسبة لمؤشر الالتزام ويشترط عرض Tier فقط. |
| `app/Http/Controllers/VolunteerTaskController.php` | نظام الـ Tier وعدّاد الحوادث (القسم 6) | مفقود | لا يوجد أي كود يحسب ترقية/تخفيض `tier` (1 إلى 3) بناءً على (`completed_tasks_count` + `average_rating`)، ولا يوجد كود يزيد عداد `reliability_incidents_count` (+1) عند الاعتذار أو التأخر، ولا إشعار الإدارة عند 3 حوادث خلال 30 يوماً. |
| `app/Models/ServiceRequest.php` | حالات الطلب constants (القسم 4.3) | يحتاج تعديل | يحتوي ثوابت لحالات غير معتمدة: `STATUS_ON_THE_WAY`, `STATUS_ARRIVED`, `STATUS_NO_SHOW`، ويفتقر لثابت الحالة المعتمدة `STATUS_ASSIGNED`. |
| `app/Http/Controllers/VolunteerTaskController.php` | مسارات ومراحل التنفيذ (القسم 4.3) | يحتاج تعديل | يحتوي دوال ومسارات لحالات ملغاة: `startHeading()` (في الطريق)، و `confirmArrival()` (وصل للموقع)، غير موجودة في الـ State Machine المعتمدة بالمرجع. |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | اعتماد الحسابات (القسم 3 و 14 شاشة 4) | مفقود | المستخدم يُسجل ويدخل مباشرة للنظام (`Auth::login`) دون المرور بدورة الاعتماد الإداري الإلزامية (`pending` -> `approved`/`rejected`) ودون توجيهه لشاشة "حسابك قيد المراجعة". |
| `app/Http/Controllers/Auth/RegisteredUserController.php` | التحقق من سن مقدم الخدمة (18+) ومستنداته (القسم 3) | يحتاج تعديل | التحقق من تاريخ الميلاد هو `before:today` فقط دون التحقق التلقائي من إتمام 18 سنة. وشهادة حسن السيرة والسلوك تُرفع للمجلد ولا تُخزن في قاعدة البيانات إطلاقاً. |
| `app/Http/Controllers/ServiceRequestController.php` | الإبلاغ عن مشكلة والتحويل للمراجعة الإدارية (القسم 4.8) | مفقود | لا توجد أي دالة أو مسار لكبير السن للإبلاغ عن مشكلة لتحويل الطلب إلى `under_review`. |
| `routes/web.php` | مسارات الإدارة والشكاوى والشهادات | مفقود | لا توجد أي مسارات لشاشات الإدارة (19-25)، ولا مسارات لتقديم أو متابعة الشكاوى (10 و 22)، ولا مسارات لطلب شهادة التطوع (15). |

---

## 3. بقايا أنظمة ملغاة تم العثور عليها

| الاسم | أين وُجد (مسار الملف) | هل لا يزال مستخدَمًا فعليًا في الكود؟ |
|---|---|---|
| `registration_profiles` | `database/migrations/2026_08_17_000001_create_registration_profiles_table.php`<br>`database/migrations/2026_08_24_000000_add_profile_photo_path_to_registration_profiles_table.php`<br>`app/Models/RegistrationProfile.php`<br>`app/Models/User.php` (دالة العلاقة)<br>`app/Http/Controllers/Auth/RegisteredUserController.php`<br>`app/Http/Controllers/ProfileController.php`<br>`app/Http/Requests/ProfileUpdateRequest.php`<br>`app/Http/Controllers/VolunteerTaskController.php`<br>`app/Http/Controllers/ServiceRequestController.php`<br>`resources/views/service-requests/index.blade.php`<br>`resources/views/provider/tasks.blade.php`<br>`resources/views/provider/dashboard.blade.php`<br>`resources/views/profile/partials/update-profile-information-form.blade.php`<br>`resources/views/dashboard.blade.php`<br>`database/seeders/DatabaseSeeder.php` | **نعم، مستخدم بشكل عميق ومحوري** في كامل عمليات التسجيل، وتعديل الملف الشخصي، وجلب أرقام الهواتف والتواصل في كافة الواجهات. |
| `account_type` | `database/migrations/2026_08_17_000000_add_account_type_to_users_table.php`<br>`app/Models/User.php`<br>`app/Http/Controllers/Auth/RegisteredUserController.php`<br>`app/Http/Requests/ProfileUpdateRequest.php`<br>`routes/web.php` (توجيه لوحة التحكم)<br>`resources/views/layouts/navigation.blade.php`<br>`resources/views/profile/edit.blade.php`<br>`resources/views/frontend/volunteer-register.blade.php`<br>`resources/views/frontend/elderly-register.blade.php`<br>`database/seeders/DatabaseSeeder.php` | **نعم، مستخدم بالكامل** وهو المحدد الوحيد لنوع الحساب (elderly / volunteer) في التوجيه والتحقق والصلاحيات بدلاً من نظام الأدوار المعتمد. |
| `commitment_score` | `database/migrations/2026_09_02_000002_create_provider_settings_table.php`<br>`app/Models/ProviderSetting.php`<br>`app/Models/User.php`<br>`app/Http/Controllers/VolunteerTaskController.php` (الأسطر 463، 519، 520)<br>`resources/views/provider/performance.blade.php` (السطر 61)<br>`database/seeders/DatabaseSeeder.php`<br>`tests/Feature/VolunteerTaskTest.php` | **نعم، شغال ونشط برمجياً**؛ يتم خصم النقاط فعلياً في عمليتي التأخير والاعتذار وتختبره اختبارات الـ Feature tests، ويظهر بالواجهة كنسبة مئوية. |
| `attendance_rate` | ملف المرجع الشامل فقط كعنصر ملغى صراحة. | **لا، غير موجود** باسمه هذا (استُبدل في migration الإعدادات باسم `punctuality_rate` ونسبته 94%). |
| `completion_rate` | `database/migrations/2026_09_02_000002_create_provider_settings_table.php`<br>`app/Models/ProviderSetting.php`<br>`app/Models/User.php`<br>`resources/views/provider/performance.blade.php` (السطور 81، 84)<br>`database/seeders/DatabaseSeeder.php` | **نعم، مستخدم** ومخزن في قاعدة البيانات ومعروض كشريط تقدم في صفحة أداء مقدم الخدمة. |
| `response_rate` | `database/migrations/2026_09_02_000002_create_provider_settings_table.php`<br>`app/Models/ProviderSetting.php`<br>`app/Models/User.php`<br>`resources/views/provider/performance.blade.php` (السطور 92، 95)<br>`database/seeders/DatabaseSeeder.php` | **نعم، مستخدم** ومخزن في قاعدة البيانات ومعروض كشريط تقدم في صفحة أداء مقدم الخدمة. |
| `provider_commitment_events` | مذكور في المرجع الشامل فقط كعنصر ملغى صراحة. | **لا، لم يُنشأ إطلاقاً** ولا توجد أي إشارة له في الكود أو الـ migrations. |

---

## 4. ملخص تنفيذي (5 أسطر كحد أقصى)

1. قاعدة البيانات الحالية متأخرة جوهرياً عن المرجع الشامل؛ إذ تفتقر لـ 6 جداول معتمدة بالكامل (`elder_profiles`, `service_provider_profiles`, `admins`, `request_attachments`, `complaints`, `volunteer_certificates`) وتعتمد على جداول ملغاة بالكامل.
2. نظام "مؤشر الالتزام" بالنقاط ونسب الأداء الملغاة لا يزال حياً وفعالاً بالكامل داخل `VolunteerTaskController` و `ProviderSetting` وواجهة الأداء، بينما نظام الـ `Tier` المعتمد وحوادث الالتزام `reliability_incidents_count` مفقودان تماماً.
3. دورة حياة الطلب غير مطابقة؛ حيث تحتوي على حالات ومسارات ملغاة (`on_the_way`, `arrived`, `no_show`) وتفتقر لحالة `assigned` وحقول التسعير وتفضيل الجنس، بالإضافة لثغرة أمنية تسمح لكبير السن بإلغاء الطلب في أي وقت عبر المتحكم.
4. نظام اعتماد المستخدمين (`pending` -> `approved`/`rejected`) مفقود بالكامل، والتسجيل لا يتحقق من شرط سن مقدم الخدمة (18+) ولا يحفظ شهادة حسن السيرة، ويسمح بالدخول المباشر.
5. يجب اتخاذ قرار استراتيجي فوري بإعادة هيكلة الـ Migrations ونماذج البيانات وإزالة الأنظمة الملغاة قبل المتابعة في بناء أي شاشات إضافية.
