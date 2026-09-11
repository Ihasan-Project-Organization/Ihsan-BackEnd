<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إحسان - إنشاء حساب مقدم خدمة (متطوع)</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Alexandria', sans-serif;
    }

    body {
      background-color: #f2ede4;
      display: flex;
      justify-content: center;
      padding: 30px 15px;
      min-height: 100vh;
      color: #333333;
    }

    .page-container {
      width: 100%;
      max-width: 1100px;
      position: relative;
    }

    /* قسم الهيدر العلوي */
    .hero-header {
      position: relative;
      width: 100%;
      height: 250px;
      border-radius: 28px 28px 0 0;
      overflow: hidden;
      background-color: #e5e3d6;
      display: flex;
      align-items: center;
    }

    .header-bg {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 1;
    }

    .volunteer-card-wrapper {
      position: absolute;
      right: 45px;
      bottom: 0;
      width: 225px;
      height: 235px;
      background: #ffffff;
      border-radius: 40px 40px 0 0;
      padding: 8px 8px 0 8px;
      box-shadow: 0 -4px 15px rgba(0,0,0,0.04);
      z-index: 5;
    }

    .volunteer-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 33px 33px 0 0;
    }

    .header-center-content {
      position: relative;
      z-index: 5;
      margin-right: 300px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 12px;
    }

    .top-action-row {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .avatar-icon {
      width: 44px;
      height: 44px;
      background-color: #d6ded0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      color: #3b5228;
      flex-shrink: 0;
    }

    .back-btn {
      color: #2b3a1d;
      text-decoration: none;
      font-weight: 700;
      font-size: 14px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s ease;
    }

    .back-btn:hover {
      opacity: 0.8;
      transform: translateX(-3px);
    }

    .title-block-text h2 {
      font-size: 26px;
      color: #2b3a1d;
      font-weight: 800;
      line-height: 1.2;
    }

    .title-block-text p {
      font-size: 14px;
      color: #555;
      margin-top: 4px;
    }

    /* كارت النموذج والحقول */
    .form-card {
      background: #ffffff;
      border-radius: 28px;
      padding: 40px 55px 35px;
      margin-top: -12px;
      position: relative;
      z-index: 10;
      box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    }

    /* Stepper */
    .stepper {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 35px;
      max-width: 850px;
      margin-left: auto;
      margin-right: auto;
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #888;
      flex: 1;
      cursor: pointer;
    }

    .step-icon {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      border: 2px solid #d0d0d0;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      background: #fff;
      color: #777;
      transition: all 0.3s ease;
    }

    .step.active .step-icon {
      background-color: #3b5228;
      color: #fff;
      border-color: #3b5228;
      box-shadow: 0 4px 12px rgba(59, 82, 40, 0.25);
    }

    .step.active span {
      color: #3b5228;
      font-weight: 700;
    }

    .step.completed .step-icon {
      background-color: #e4ebd9;
      color: #3b5228;
      border-color: #3b5228;
    }

    .step-line {
      flex: 1;
      border-top: 2px dashed #ccc;
      margin-bottom: 22px;
      transition: all 0.3s ease;
    }

    .step-line.completed {
      border-color: #3b5228;
    }

    .form-step-content {
      display: none;
    }

    .form-step-content.active {
      display: block;
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px 30px;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .input-group label {
      font-size: 13.5px;
      font-weight: 700;
      color: #2d3748;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-wrapper input {
      width: 100%;
      padding: 13px 42px;
      border: 1.5px solid #d2d6dc;
      border-radius: 12px;
      font-size: 13.5px;
      outline: none;
      transition: all 0.2s;
      background: #fafaf9;
    }

    .input-wrapper input:focus {
      border-color: #3b5228;
      background: #ffffff;
      box-shadow: 0 0 0 3px rgba(59, 82, 40, 0.15);
    }

    .input-wrapper .field-icon {
      position: absolute;
      right: 14px;
      color: #3b5228;
      font-size: 15px;
      pointer-events: none;
    }

    .input-wrapper .toggle-password {
      position: absolute;
      left: 14px;
      color: #777;
      font-size: 15px;
      cursor: pointer;
      transition: color 0.2s;
    }

    .input-wrapper .toggle-password:hover {
      color: #3b5228;
    }

    .upload-box {
      border: 2px dashed #cbd5e1;
      border-radius: 18px;
      padding: 24px;
      text-align: center;
      background: #fafaf9;
      transition: all 0.2s;
      cursor: pointer;
    }

    .upload-box:hover {
      border-color: #3b5228;
      background: #f4f6f0;
    }

    .upload-box .upload-icon {
      font-size: 32px;
      color: #3b5228;
      margin-bottom: 8px;
    }

    .upload-box .file-chosen-name {
      margin-top: 8px;
      font-size: 12px;
      font-weight: 700;
      color: #2d3748;
    }

    .btn-container {
      display: flex;
      gap: 12px;
      margin-top: 30px;
    }

    .submit-btn, .next-btn, .prev-btn {
      padding: 13px 26px;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.2s;
      font-family: inherit;
      border: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .submit-btn, .next-btn {
      flex: 1;
      background-color: #3b5228;
      color: white;
      box-shadow: 0 4px 12px rgba(59, 82, 40, 0.2);
    }

    .submit-btn:hover, .next-btn:hover {
      background-color: #2e411f;
    }

    .prev-btn {
      background-color: #f1f5f9;
      color: #475569;
      border: 1px solid #cbd5e1;
    }

    .prev-btn:hover {
      background-color: #e2e8f0;
    }

    .info-summary-card {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      padding: 22px;
      margin-bottom: 24px;
    }

    .info-summary-card h4 {
      font-size: 15px;
      color: #3b5228;
      font-weight: 800;
      margin-bottom: 14px;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .summary-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px 24px;
      font-size: 13px;
    }

    .summary-item {
      display: flex;
      flex-direction: column;
      gap: 3px;
    }

    .summary-item .label {
      color: #64748b;
      font-size: 11px;
      font-weight: 600;
    }

    .summary-item .value {
      color: #1e293b;
      font-weight: 700;
    }

    .terms-group {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin: 20px 0;
      font-size: 13px;
      color: #4b5563;
    }

    .terms-group input {
      width: 18px;
      height: 18px;
      accent-color: #3b5228;
      cursor: pointer;
    }

    .login-redirect {
      text-align: center;
      margin-top: 22px;
      font-size: 13px;
      color: #6b7280;
    }

    .login-redirect a {
      color: #3b5228;
      font-weight: 700;
      text-decoration: none;
    }

    .login-redirect a:hover {
      text-decoration: underline;
    }

    .alert-danger {
      background-color: #fef2f2;
      border: 1px solid #fecaca;
      color: #b91c1c;
      padding: 14px 18px;
      border-radius: 14px;
      margin-bottom: 24px;
      font-size: 13px;
    }

    .alert-danger ul {
      margin-right: 18px;
    }

    @media (max-width: 768px) {
      .hero-header {
        height: auto;
        padding: 20px;
        flex-direction: column;
        align-items: center;
      }

      .volunteer-card-wrapper {
        position: relative;
        right: 0;
        height: 160px;
        margin-bottom: 12px;
      }

      .header-center-content {
        margin-right: 0;
        align-items: center;
        text-align: center;
      }

      .form-card {
        padding: 25px 20px;
      }

      .form-grid {
        grid-template-columns: 1fr;
      }

      .stepper span {
        display: none;
      }
    }
  </style>
</head>
<body>

  <div class="page-container">
    
    <!-- قسم الهيدر العلوي -->
    <div class="hero-header">
      <img src="{{ asset('assets/img/volheader.jpeg') }}" alt="Header Background" class="header-bg" onerror="this.src='{{ asset('assets/img/header.jpeg') }}'">
      
      <div class="volunteer-card-wrapper">
        <img src="{{ asset('assets/img/vol.jpeg') }}" alt="متطوع" class="volunteer-img">
      </div>

      <div class="header-center-content">
        <div class="top-action-row">
          <div class="avatar-icon">
            <i class="fa-solid fa-hand-holding-heart"></i>
          </div>
          <a href="{{ route('login') }}" class="back-btn">
            الرجوع لتسجيل الدخول <i class="fa-solid fa-arrow-left"></i>
          </a>
        </div>

        <div class="title-block-text">
          <h2>إنشاء حساب مقدم خدمة (متطوع)</h2>
          <p>انضم إلى منصة إحسان لتقديم الرعاية والعون لكبار السن واحتساب الأجر</p>
        </div>
      </div>
    </div>

    <!-- كارت النموذج متعدد الخطوات -->
    <div class="form-card">

      <!-- شريط الخطوات المعتمد -->
      <div class="stepper">
        <div class="step active" id="step-indicator-1" onclick="goToStep(1)">
          <div class="step-icon"><i class="fa-solid fa-user"></i></div>
          <span>البيانات الشخصية</span>
        </div>
        <div class="step-line" id="line-1"></div>
        <div class="step" id="step-indicator-2" onclick="goToStep(2)">
          <div class="step-icon"><i class="fa-solid fa-file-shield"></i></div>
          <span>الوثائق الرسمية</span>
        </div>
        <div class="step-line" id="line-2"></div>
        <div class="step" id="step-indicator-3" onclick="goToStep(3)">
          <div class="step-icon"><i class="fa-solid fa-clipboard-check"></i></div>
          <span>المراجعة والتأكيد</span>
        </div>
      </div>

      {{-- رسائل الخطأ من السيرفر --}}
      @if ($errors->any())
        <div class="alert-danger">
          <strong>يرجى تصحيح الأخطاء التالية:</strong>
          <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- النموذج الفعلي الموحد -->
      <form id="registrationForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="role" value="provider">

        <!-- الخطوة الأولى: البيانات الشخصية -->
        <div class="form-step-content active" id="step-1">
          <div class="form-grid">
            <div class="input-group">
              <label for="name">الاسم بالكامل <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-regular fa-user field-icon"></i>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="ادخل اسمك الرباعي" required autofocus>
              </div>
            </div>

            <div class="input-group">
              <label for="id_number">رقم الهوية الوطنية / الإقامة <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-regular fa-id-card field-icon"></i>
                <input type="text" id="id_number" name="id_number" value="{{ old('id_number') }}" placeholder="أدخل رقم الهوية" required>
              </div>
            </div>

            <div class="input-group">
              <label for="dob">تاريخ الميلاد (شرط 18 سنة فأكثر) <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-regular fa-calendar field-icon"></i>
                <input type="date" id="dob" name="dob" value="{{ old('dob') }}" max="{{ now()->subYears(18)->format('Y-m-d') }}" required>
              </div>
              <span class="text-xs text-slate-500">يجب ألا يقل عمر المتطوع عن 18 عامًا كاملة.</span>
            </div>

            <div class="input-group">
              <label for="phone_number">رقم الجوال <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-solid fa-phone field-icon"></i>
                <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') ?? old('phone') }}" placeholder="059XXXXXXX" required>
                <input type="hidden" id="phone" name="phone" value="{{ old('phone_number') ?? old('phone') }}">
              </div>
            </div>

            <div class="input-group">
              <label for="email">البريد الإلكتروني <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-regular fa-envelope field-icon"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required autocomplete="username">
              </div>
            </div>

            <div class="input-group">
              <label for="password">كلمة المرور <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-solid fa-lock field-icon"></i>
                <input type="password" id="password" name="password" placeholder="٨ خانات على الأقل" minlength="8" required autocomplete="new-password">
                <i class="fa-regular fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
              </div>
            </div>

            <div class="input-group md:col-span-2">
              <label for="password_confirmation">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-solid fa-lock field-icon"></i>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="أعد إدخال كلمة المرور" minlength="8" required autocomplete="new-password">
                <i class="fa-regular fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
              </div>
            </div>
          </div>

          <div class="btn-container">
            <button type="button" class="next-btn" onclick="nextStep(1)">
              <span>التالي: رفع الوثائق الرسمية</span>
              <i class="fa-solid fa-arrow-left"></i>
            </button>
          </div>
        </div>

        <!-- الخطوة الثانية: الوثائق الرسمية -->
        <div class="form-step-content" id="step-2">
          <div class="grid gap-6 md:grid-cols-2 mb-4">
            
            <!-- وثيقة الهوية الشخصية -->
            <div class="input-group">
              <label for="id_document">صورة الهوية الوطنية الشخصية <span class="text-red-500">*</span></label>
              <label for="id_document" class="upload-box block">
                <div class="upload-icon"><i class="fa-solid fa-id-card"></i></div>
                <strong class="text-sm block text-slate-700">اضغط لرفع صورة الهوية</strong>
                <span class="text-xs text-slate-500 block mt-1">الملفات المدعومة: JPG, PNG, PDF (حد أقصى 5MB)</span>
                <input type="file" id="id_document" name="id_document" accept=".png,.jpg,.jpeg,.pdf" required class="hidden" onchange="updateFileName(this, 'id-file-name')">
                <div id="id-file-name" class="file-chosen-name text-[#3b5228]">لم يتم اختيار ملف بعد</div>
              </label>
            </div>

            <!-- شهادة حسن السيرة والسلوك -->
            <div class="input-group">
              <label for="conduct_document">شهادة حسن السيرة والسلوك (عدم محكومية) <span class="text-red-500">*</span></label>
              <label for="conduct_document" class="upload-box block">
                <div class="upload-icon"><i class="fa-solid fa-file-shield"></i></div>
                <strong class="text-sm block text-slate-700">اضغط لرفع شهادة حسن السيرة</strong>
                <span class="text-xs text-slate-500 block mt-1">الملفات المدعومة: JPG, PNG, PDF (حد أقصى 5MB)</span>
                <input type="file" id="conduct_document" name="conduct_document" accept=".png,.jpg,.jpeg,.pdf" required class="hidden" onchange="updateFileName(this, 'conduct-file-name')">
                <div id="conduct-file-name" class="file-chosen-name text-[#3b5228]">لم يتم اختيار ملف بعد</div>
              </label>
            </div>

          </div>

          <div class="bg-[#f4f6f0] p-4 rounded-xl border border-[#d6ded0] text-xs text-[#3b5228] mb-4">
            <i class="fa-solid fa-circle-info ml-1"></i>
            تخضع كافة الوثائق للتدقيق والمصادقة الأمنية والإدارية قبل تفعيل الحساب لضمان أمان وموثوقية خدمات كبار السن.
          </div>

          <div class="btn-container">
            <button type="button" class="prev-btn" onclick="prevStep(2)">
              <i class="fa-solid fa-arrow-right"></i>
              <span>السابق</span>
            </button>
            <button type="button" class="next-btn" onclick="nextStep(2)">
              <span>التالي: المراجعة والتأكيد</span>
              <i class="fa-solid fa-arrow-left"></i>
            </button>
          </div>
        </div>

        <!-- الخطوة الثالثة: المراجعة والتأكيد النهائي (من تصميم volchek.html) -->
        <div class="form-step-content" id="step-3">
          
          <!-- ملخص المعلومات المدخلة والوثائق -->
          <div class="info-summary-card">
            <h4><i class="fa-solid fa-circle-check ml-1 text-[#3b5228]"></i> مراجعة نهائية لبيانات التسجيل:</h4>
            <div class="summary-grid">
              <div class="summary-item">
                <span class="label">الاسم بالكامل:</span>
                <span class="value" id="summary-name">—</span>
              </div>
              <div class="summary-item">
                <span class="label">رقم الهوية:</span>
                <span class="value" id="summary-id">—</span>
              </div>
              <div class="summary-item">
                <span class="label">تاريخ الميلاد:</span>
                <span class="value" id="summary-dob">—</span>
              </div>
              <div class="summary-item">
                <span class="label">رقم الجوال:</span>
                <span class="value" id="summary-phone">—</span>
              </div>
              <div class="summary-item">
                <span class="label">البريد الإلكتروني:</span>
                <span class="value" id="summary-email">—</span>
              </div>
              <div class="summary-item">
                <span class="label">الوثائق المرفقة:</span>
                <span class="value text-[#3b5228]" id="summary-docs">الهوية + حسن السيرة</span>
              </div>
            </div>
          </div>

          <div class="terms-group">
            <input type="checkbox" id="terms" name="terms" required checked>
            <label for="terms">أتعهد بصحة كافة البيانات والوثائق المرفقة، وأوافق على <a href="#" class="text-[#3b5228] font-bold underline">ميثاق التطوع والشروط والأحكام</a> لمنصة إحسان.</label>
          </div>

          <div class="btn-container">
            <button type="button" class="prev-btn" onclick="prevStep(3)">
              <i class="fa-solid fa-arrow-right"></i>
              <span>السابق</span>
            </button>
            <button type="submit" class="submit-btn" id="submitVolunteerFormBtn">
              <i class="fa-solid fa-paper-plane"></i>
              <span>إرسال طلب الانضمام</span>
            </button>
          </div>
        </div>

      </form>

      <div class="login-redirect">
        <span>لديك حساب بالفعل؟ </span>
        <a href="{{ route('login') }}">تسجيل الدخول</a>
      </div>

    </div>
  </div>

  <script>
    let currentStep = 1;

    function syncPhoneInputs() {
      const phoneVal = document.getElementById('phone_number').value;
      const hiddenPhone = document.getElementById('phone');
      if (hiddenPhone) hiddenPhone.value = phoneVal;
    }
    document.getElementById('phone_number')?.addEventListener('input', syncPhoneInputs);

    function updateFileName(input, targetId) {
      const target = document.getElementById(targetId);
      if (input.files && input.files.length > 0) {
        target.textContent = '✓ ' + input.files[0].name;
        target.style.color = '#3b5228';
      } else {
        target.textContent = 'لم يتم اختيار ملف بعد';
        target.style.color = '#777';
      }
    }

    function updateSummary() {
      document.getElementById('summary-name').textContent = document.getElementById('name').value || '—';
      document.getElementById('summary-id').textContent = document.getElementById('id_number').value || '—';
      document.getElementById('summary-dob').textContent = document.getElementById('dob').value || '—';
      document.getElementById('summary-phone').textContent = document.getElementById('phone_number').value || '—';
      document.getElementById('summary-email').textContent = document.getElementById('email').value || '—';

      const idDoc = document.getElementById('id_document');
      const conductDoc = document.getElementById('conduct_document');
      let docsStatus = [];
      if (idDoc.files.length > 0) docsStatus.push('الهوية: ' + idDoc.files[0].name);
      if (conductDoc.files.length > 0) docsStatus.push('حسن السيرة: ' + conductDoc.files[0].name);
      document.getElementById('summary-docs').textContent = docsStatus.length > 0 ? docsStatus.join(' | ') : 'مرفوعة';
    }

    function showStep(step) {
      document.querySelectorAll('.form-step-content').forEach(el => el.classList.remove('active'));
      const target = document.getElementById('step-' + step);
      if (target) target.classList.add('active');

      // تحديث شريط الستيبر
      for (let i = 1; i <= 3; i++) {
        const ind = document.getElementById('step-indicator-' + i);
        if (ind) {
          ind.classList.remove('active', 'completed');
          if (i === step) ind.classList.add('active');
          else if (i < step) ind.classList.add('completed');
        }
        if (i < 3) {
          const line = document.getElementById('line-' + i);
          if (line) {
            if (i < step) line.classList.add('completed');
            else line.classList.remove('completed');
          }
        }
      }

      if (step === 3) updateSummary();
      currentStep = step;
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateStep(step) {
      const stepEl = document.getElementById('step-' + step);
      if (!stepEl) return true;

      const requiredInputs = stepEl.querySelectorAll('input[required], select[required]');
      for (const input of requiredInputs) {
        if (!input.checkValidity()) {
          input.reportValidity();
          return false;
        }
      }

      if (step === 1) {
        // فحص شرط السن 18+ في الواجهة الأمامية
        const dobInput = document.getElementById('dob');
        if (dobInput && dobInput.value) {
          const birthDate = new Date(dobInput.value);
          const today = new Date();
          let age = today.getFullYear() - birthDate.getFullYear();
          const m = today.getMonth() - birthDate.getMonth();
          if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
          }
          if (age < 18) {
            alert('يجب ألا يقل عمر مقدم الخدمة (المتطوع) عن 18 عاماً.');
            dobInput.focus();
            return false;
          }
        }

        const p1 = document.getElementById('password').value;
        const p2 = document.getElementById('password_confirmation').value;
        if (p1 && p2 && p1 !== p2) {
          alert('كلمة المرور وتأكيدها غير متطابقين.');
          document.getElementById('password_confirmation').focus();
          return false;
        }
      }

      if (step === 2) {
        const idDoc = document.getElementById('id_document');
        const conductDoc = document.getElementById('conduct_document');
        if (!idDoc.files || idDoc.files.length === 0) {
          alert('يرجى رفع صورة الهوية الشخصية للمتابعة.');
          return false;
        }
        if (!conductDoc.files || conductDoc.files.length === 0) {
          alert('يرجى رفع شهادة حسن السيرة والسلوك للمتابعة.');
          return false;
        }
      }

      return true;
    }

    function nextStep(step) {
      if (!validateStep(step)) return;
      showStep(step + 1);
    }

    function prevStep(step) {
      showStep(step - 1);
    }

    function goToStep(step) {
      if (step > currentStep) {
        if (!validateStep(currentStep)) return;
      }
      showStep(step);
    }

    function togglePassword(inputId, icon) {
      const input = document.getElementById(inputId);
      if (!input) return;
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }

    // إذا وُجدت أخطاء بالخطوة الثانية (الوثائق) عند العودة من السيرفر
    @if ($errors->has('id_document') || $errors->has('conduct_document'))
      showStep(2);
    @endif
  </script>

</body>
</html>
