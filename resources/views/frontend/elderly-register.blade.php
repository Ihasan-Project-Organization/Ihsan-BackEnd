<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إحسان - إنشاء حساب كبير سن</title>
  
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
      height: 240px;
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

    .oldage-card-wrapper {
      position: absolute;
      right: 45px;
      bottom: 0;
      width: 220px;
      height: 220px;
      background: #ffffff;
      border-radius: 40px 40px 0 0;
      padding: 7px 7px 0 7px;
      box-shadow: 0 -4px 15px rgba(0,0,0,0.04);
      z-index: 5;
    }

    .oldage-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 33px 33px 0 0;
    }

    .header-center-content {
      position: relative;
      z-index: 5;
      margin-right: 295px;
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

    .input-wrapper input,
    .input-wrapper select {
      width: 100%;
      padding: 13px 42px;
      border: 1.5px solid #d2d6dc;
      border-radius: 12px;
      font-size: 13.5px;
      outline: none;
      transition: all 0.2s;
      background: #fafaf9;
    }

    .input-wrapper input:focus,
    .input-wrapper select:focus {
      border-color: #3b5228;
      background: #ffffff;
      box-shadow: 0 0 0 3px rgba(59, 82, 40, 0.15);
    }

    .input-wrapper input.is-invalid,
    .input-wrapper select.is-invalid {
      border-color: #ef4444 !important;
      background-color: #fef2f2 !important;
    }

    .field-error-msg {
      color: #b91c1c;
      font-size: 11.5px;
      font-weight: 700;
      margin-top: 5px;
      display: flex;
      align-items: center;
      gap: 4px;
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
      padding: 20px;
      margin-bottom: 24px;
    }

    .info-summary-card h4 {
      font-size: 14px;
      color: #3b5228;
      font-weight: 800;
      margin-bottom: 14px;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 8px;
    }

    .summary-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px 20px;
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
      width: 17px;
      height: 17px;
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

      .oldage-card-wrapper {
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
      <img src="{{ asset('assets/img/header.jpeg') }}" alt="Header Background" class="header-bg">
      
      <div class="oldage-card-wrapper">
        <img src="{{ asset('assets/img/oldage.jpeg') }}" alt="كبير سن" class="oldage-img">
      </div>

      <div class="header-center-content">
        <div class="top-action-row">
          <div class="avatar-icon">
            <i class="fa-solid fa-user"></i>
          </div>
          <a href="{{ route('login') }}" class="back-btn">
            الرجوع لتسجيل الدخول <i class="fa-solid fa-arrow-left"></i>
          </a>
        </div>

        <div class="title-block-text">
          <h2>إنشاء حساب كبير سن</h2>
          <p>أنشئ حسابك الآن لتتمكن من طلب المساعدة والخدمات بسهولة</p>
        </div>
      </div>
    </div>

    <!-- كارت النموذج متعدد الخطوات -->
    <div class="form-card">

      <!-- شريط الخطوات المعتمد -->
      <div class="stepper">
        <div class="step active" id="step-indicator-1" onclick="goToStep(1)">
          <div class="step-icon"><i class="fa-solid fa-user"></i></div>
          <span>المعلومات الشخصية</span>
        </div>
        <div class="step-line" id="line-1"></div>
        <div class="step" id="step-indicator-2" onclick="goToStep(2)">
          <div class="step-icon"><i class="fa-solid fa-house"></i></div>
          <span>مكان السكن والتواصل</span>
        </div>
        <div class="step-line" id="line-2"></div>
        <div class="step" id="step-indicator-3" onclick="goToStep(3)">
          <div class="step-icon"><i class="fa-solid fa-clipboard-check"></i></div>
          <span>المراجعة والمرفقات</span>
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
        <input type="hidden" name="role" value="elder">

        <!-- الخطوة الأولى: المعلومات الشخصية -->
        <div class="form-step-content active" id="step-1">
          <div class="form-grid">
            <div class="input-group">
              <label for="name">الاسم بالكامل <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-regular fa-user field-icon"></i>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="ادخل اسمك الكامل" class="{{ $errors->has('name') ? 'is-invalid' : '' }}" required autofocus>
              </div>
              @error('name')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="input-group">
              <label for="id_number">رقم الهوية الوطنية <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-solid fa-id-card field-icon"></i>
                <input type="text" id="id_number" name="id_number" value="{{ old('id_number') }}" placeholder="أدخل رقم الهوية الشخصية (9 أرقام)" class="{{ $errors->has('id_number') ? 'is-invalid' : '' }}" required>
              </div>
              @error('id_number')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="input-group">
              <label for="email">البريد الإلكتروني <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-regular fa-envelope field-icon"></i>
                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" class="{{ $errors->has('email') ? 'is-invalid' : '' }}" required autocomplete="username">
              </div>
              @error('email')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="input-group">
              <label for="password">كلمة المرور <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-solid fa-lock field-icon"></i>
                <input type="password" id="password" name="password" placeholder="٨ خانات على الأقل" minlength="8" class="{{ $errors->has('password') ? 'is-invalid' : '' }}" required autocomplete="new-password">
                <i class="fa-regular fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
              </div>
              @error('password')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="input-group">
              <label for="password_confirmation">تأكيد كلمة المرور <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-solid fa-lock field-icon"></i>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="أعد كتابة كلمة المرور" minlength="8" class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" required autocomplete="new-password">
                <i class="fa-regular fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
              </div>
              @error('password_confirmation')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="input-group">
              <label for="dob">تاريخ الميلاد</label>
              <div class="input-wrapper">
                <i class="fa-regular fa-calendar field-icon"></i>
                <input type="date" id="dob" name="dob" value="{{ old('dob') }}" class="{{ $errors->has('dob') ? 'is-invalid' : '' }}">
              </div>
              @error('dob')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="input-group">
              <label for="gender">الجنس</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-venus-mars field-icon"></i>
                <select id="gender" name="gender" class="{{ $errors->has('gender') ? 'is-invalid' : '' }}">
                  <option value="">اختر الجنس</option>
                  <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>ذكر</option>
                  <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>أنثى</option>
                </select>
              </div>
              @error('gender')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="btn-container">
            <button type="button" class="next-btn" onclick="nextStep(1)">
              <span>التالي: مكان السكن والتواصل</span>
              <i class="fa-solid fa-arrow-left"></i>
            </button>
          </div>
        </div>

        <!-- الخطوة الثانية: مكان السكن والتواصل -->
        <div class="form-step-content" id="step-2">
          <div class="form-grid">
            <div class="input-group">
              <label for="city">المدينة / المحافظة <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-solid fa-city field-icon"></i>
                <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="مثال: غزة، خانيونس، رام الله" class="{{ $errors->has('city') ? 'is-invalid' : '' }}" required>
              </div>
              @error('city')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="input-group">
              <label for="phone_number">رقم الهاتف / الجوال <span class="text-red-500">*</span></label>
              <div class="input-wrapper">
                <i class="fa-solid fa-phone field-icon"></i>
                <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number') ?? old('phone') }}" placeholder="059XXXXXXX" class="{{ $errors->has('phone_number') || $errors->has('phone') ? 'is-invalid' : '' }}" required>
                {{-- إبقاء اسم phone احتياطياً لتوافقية الاختبارات السابقة --}}
                <input type="hidden" id="phone" name="phone" value="{{ old('phone_number') ?? old('phone') }}">
              </div>
              @error('phone_number')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
              @error('phone')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="input-group">
              <label for="address">العنوان التفصيلي</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-location-dot field-icon"></i>
                <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="الحي، الشارع، أقرب معلم" class="{{ $errors->has('address') ? 'is-invalid' : '' }}">
              </div>
              @error('address')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>

            <div class="input-group">
              <label for="housing_type">نوع السكن</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-building field-icon"></i>
                <select id="housing_type" name="housing_type" class="{{ $errors->has('housing_type') ? 'is-invalid' : '' }}">
                  <option value="">اختر نوع السكن</option>
                  <option value="independent" {{ old('housing_type') === 'independent' ? 'selected' : '' }}>منزل مستقل</option>
                  <option value="apartment" {{ old('housing_type') === 'apartment' ? 'selected' : '' }}>شقة سكنية</option>
                  <option value="with_family" {{ old('housing_type') === 'with_family' ? 'selected' : '' }}>مع العائلة</option>
                </select>
              </div>
              @error('housing_type')
                <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
              @enderror
            </div>
          </div>

          <div class="btn-container">
            <button type="button" class="prev-btn" onclick="prevStep(2)">
              <i class="fa-solid fa-arrow-right"></i>
              <span>السابق</span>
            </button>
            <button type="button" class="next-btn" onclick="nextStep(2)">
              <span>التالي: المراجعة والمرفقات</span>
              <i class="fa-solid fa-arrow-left"></i>
            </button>
          </div>
        </div>

        <!-- الخطوة الثالثة: المراجعة والمرفقات وتأكيد الطلب -->
        <div class="form-step-content" id="step-3">
          
          <!-- ملخص المعلومات المدخلة -->
          <div class="info-summary-card">
            <h4><i class="fa-solid fa-list-check ml-1 text-[#3b5228]"></i> مراجعة سريعة للبيانات المدخلة:</h4>
            <div class="summary-grid">
              <div class="summary-item">
                <span class="label">الاسم بالكامل:</span>
                <span class="value" id="summary-name">—</span>
              </div>
              <div class="summary-item">
                <span class="label">رقم الهوية:</span>
                <span class="value" id="summary-id-number">—</span>
              </div>
              <div class="summary-item">
                <span class="label">البريد الإلكتروني:</span>
                <span class="value" id="summary-email">—</span>
              </div>
              <div class="summary-item">
                <span class="label">المدينة:</span>
                <span class="value" id="summary-city">—</span>
              </div>
              <div class="summary-item">
                <span class="label">رقم الهاتف:</span>
                <span class="value" id="summary-phone">—</span>
              </div>
            </div>
          </div>

          <!-- رفع إثبات الهوية (اختياري كما هو معتمد) -->
          <div class="input-group mb-4">
            <label for="id_document">صورة الهوية أو إثبات الشخصية (اختياري)</label>
            <div class="border-2 border-dashed border-slate-300 rounded-2xl p-5 text-center bg-[#fafaf9] hover:border-[#3b5228] transition cursor-pointer">
              <input type="file" id="id_document" name="id_document" accept=".png,.jpg,.jpeg,.pdf" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#e4ebd9] file:text-[#3b5228] hover:file:bg-[#d6ded0]">
              <p class="text-xs text-slate-400 mt-2">الملفات المسموحة: JPG, PNG, PDF (الحد الأقصى: 5 ميجابايت)</p>
            </div>
            @error('id_document')
              <div class="field-error-msg"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</div>
            @enderror
          </div>

          <div class="terms-group">
            <input type="checkbox" id="terms" name="terms" required checked>
            <label for="terms">أوافق على <a href="#" class="text-[#3b5228] font-bold underline">الشروط والأحكام</a> وسياسة الخصوصية لمنصة إحسان</label>
          </div>

          <div class="btn-container">
            <button type="button" class="prev-btn" onclick="prevStep(3)">
              <i class="fa-solid fa-arrow-right"></i>
              <span>السابق</span>
            </button>
            <button type="submit" class="submit-btn" id="submitFormBtn">
              <i class="fa-solid fa-check"></i>
              <span>إرسال طلب إنشاء الحساب</span>
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

    function updateSummary() {
      document.getElementById('summary-name').textContent = document.getElementById('name').value || '—';
      const idNumEl = document.getElementById('id_number');
      const sumIdEl = document.getElementById('summary-id-number');
      if (idNumEl && sumIdEl) sumIdEl.textContent = idNumEl.value || '—';
      document.getElementById('summary-email').textContent = document.getElementById('email').value || '—';
      document.getElementById('summary-city').textContent = document.getElementById('city').value || '—';
      document.getElementById('summary-phone').textContent = document.getElementById('phone_number').value || '—';
    }

    function showStep(step) {
      document.querySelectorAll('.form-step-content').forEach(el => el.classList.remove('active'));
      const target = document.getElementById('step-' + step);
      if (target) target.classList.add('active');

      // تحديث الستيبر
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
        const p1 = document.getElementById('password').value;
        const p2 = document.getElementById('password_confirmation').value;
        if (p1 && p2 && p1 !== p2) {
          alert('كلمة المرور وتأكيدها غير متطابقين.');
          document.getElementById('password_confirmation').focus();
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

    // إذا وُجدت أخطاء بالتحقق عند العودة من السيرفر، نبقى في الخطوة المعنية
    @if ($errors->has('id_document'))
      showStep(3);
    @elseif ($errors->has('city') || $errors->has('phone') || $errors->has('phone_number') || $errors->has('address') || $errors->has('housing_type'))
      showStep(2);
    @elseif ($errors->any())
      showStep(1);
    @endif
  </script>

</body>
</html>
