<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إحسان - إنشاء حساب كبير سن</title>
  
  <!-- استدعاء خط Alexandria من Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- أيقونات FontAwesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
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
      padding: 20px 0;
      min-height: 100vh;
    }

    .page-container {
      width: 96%;
      max-width: 1200px;
      position: relative;
    }

    /* =========================================
        قسم الهيدر العلوي
        ========================================= */
    .hero-header {
      position: relative;
      width: 100%;
      height: 240px;
      border-radius: 24px 24px 0 0;
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
      gap: 14px;
    }

    .top-action-row {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .avatar-icon {
      width: 42px;
      height: 42px;
      background-color: #d6ded0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
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
      color: #666;
      margin-top: 6px;
    }

    /* =========================================
        كارت النموذج والحقول
        ========================================= */
    .form-card {
      background: #ffffff;
      border-radius: 24px;
      padding: 40px 60px 35px;
      margin-top: -10px;
      position: relative;
      z-index: 10;
      box-shadow: 0 10px 30px rgba(0,0,0,0.04);
    }

    .stepper {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 35px;
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      font-size: 13px;
      font-weight: 600;
      color: #777;
      flex: 1;
    }

    .step-icon {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      border: 1.5px solid #d0d0d0;
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
    }

    .form-step-content {
      display: none;
    }

    .form-step-content.active {
      display: block;
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
      font-weight: 600;
      color: #333;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-wrapper input {
      width: 100%;
      padding: 14px 42px;
      border: 1.5px solid #3b5228;
      border-radius: 12px;
      font-size: 13.5px;
      outline: none;
      transition: box-shadow 0.2s;
    }

    /* تحسين إضافي لحقل التقويم */
    .input-wrapper input[type="date"] {
      cursor: pointer;
    }
    .input-wrapper input[type="date"]::-webkit-calendar-picker-indicator {
      opacity: 0;
      cursor: pointer;
      position: absolute;
      right: 0;
      left: 0;
      top: 0;
      bottom: 0;
      width: 100%;
      height: 100%;
    }

    .input-wrapper input:focus {
      box-shadow: 0 0 0 3px rgba(59, 82, 40, 0.15);
    }

    .input-wrapper .field-icon {
      position: absolute;
      right: 14px;
      color: #3b5228;
      font-size: 15px;
      pointer-events: none;
    }

    .input-wrapper .clickable-icon {
      pointer-events: auto;
      cursor: pointer;
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

    .info-note {
      background-color: #e4ebd9;
      color: #3b5228;
      text-align: center;
      padding: 14px;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      margin: 25px 0 20px;
    }

    .terms-group {
      display: flex;
      justify-content: center;
      margin-bottom: 22px;
      font-size: 13.5px;
      font-weight: 500;
      color: #444;
    }

    .terms-group input {
      margin-left: 8px;
      accent-color: #3b5228;
      width: 17px;
      height: 17px;
      cursor: pointer;
    }

    .btn-container {
      display: flex;
      gap: 12px;
    }

    .submit-btn, .prev-btn {
      width: 100%;
      padding: 14px;
      background-color: #3b5228;
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .prev-btn {
      background-color: #777;
      display: none;
    }

    .submit-btn:hover {
      background-color: #2e411f;
    }

    .login-redirect {
      text-align: center;
      margin-top: 20px;
      font-size: 13.5px;
    }

    .login-redirect a {
      color: #3b5228;
      font-weight: 700;
      text-decoration: none;
      margin-right: 5px;
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
        margin-bottom: 15px;
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
    }
  </style>
</head>
<body>

  <div class="page-container">
    
    <!-- قسم الهيدر -->
    <div class="hero-header">
      <img src="{{ asset('assets/img/header.jpeg') }}" alt="Header Background" class="header-bg">
      
      <div class="oldage-card-wrapper">
        <img src="{{ asset('assets/img/oldage.jpeg') }}" alt="Old Age" class="oldage-img">
      </div>

      <div class="header-center-content">
        <div class="top-action-row">
          <div class="avatar-icon">
            <i class="fa-solid fa-user"></i>
          </div>
          <a href="{{ route('frontend.login') }}" class="back-btn">
            الرجوع <i class="fa-solid fa-arrow-left"></i>
          </a>
        </div>

        <div class="title-block-text">
          <h2>إنشاء حساب كبير سن</h2>
          <p>أنشئ حسابك الآن لتتمكن من طلب الخدمات بسهولة</p>
        </div>
      </div>
    </div>

    <!-- كارت النموذج -->
    <div class="form-card">
      <div class="stepper">
        <div class="step active" id="step-indicator-1">
          <div class="step-icon"><i class="fa-solid fa-user"></i></div>
          <span>المعلومات الشخصية</span>
        </div>
        <div class="step-line"></div>
        <div class="step" id="step-indicator-2">
          <div class="step-icon"><i class="fa-solid fa-house"></i></div>
          <span>مكان السكن</span>
        </div>
        <div class="step-line"></div>
        <div class="step" id="step-indicator-3">
          <div class="step-icon"><i class="fa-solid fa-upload"></i></div>
          <span>مراجعة وإرسال</span>
        </div>
      </div>

      <form id="registrationForm" onsubmit="handleFormSubmit(event)">
        
        <!-- الخطوة الأولى: المعلومات الشخصية -->
        <div class="form-step-content active" id="step-1">
          <div class="form-grid">
            <div class="input-group">
              <label>الاسم بالكامل</label>
              <div class="input-wrapper">
                <i class="fa-regular fa-pen-to-square field-icon"></i>
                <input type="text" id="fullName" placeholder="ادخل اسمك كامل" required>
              </div>
            </div>

            <!-- حقل تاريخ الميلاد مع تقويم متفاعل -->
            <div class="input-group">
              <label>تاريخ الميلاد</label>
              <div class="input-wrapper">
                <i class="fa-regular fa-calendar field-icon clickable-icon" onclick="openDatePicker()"></i>
                <input type="date" id="dob" required onclick="openDatePicker()">
              </div>
            </div>

            <div class="input-group">
              <label>رقم الجوال</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-phone field-icon"></i>
                <input type="tel" id="phone" placeholder="05XXXXXXXX" required>
              </div>
            </div>

            <div class="input-group">
              <label>البريد الإلكتروني</label>
              <div class="input-wrapper">
                <i class="fa-regular fa-envelope field-icon"></i>
                <input type="email" id="email" placeholder="ادخل بريدك الإلكتروني" required>
              </div>
            </div>

            <div class="input-group">
              <label>كلمة المرور</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-lock field-icon"></i>
                <input type="password" id="password" placeholder="ادخل كلمة المرور" required>
                <i class="fa-regular fa-eye toggle-password" onclick="togglePasswordVisibility('password', this)"></i>
              </div>
            </div>

            <div class="input-group">
              <label>تأكيد كلمة المرور</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-lock field-icon"></i>
                <input type="password" id="confirmPassword" placeholder="أعد إدخال كلمة المرور" required>
                <i class="fa-regular fa-eye toggle-password" onclick="togglePasswordVisibility('confirmPassword', this)"></i>
              </div>
            </div>
          </div>
        </div>

        <!-- الخطوة الثانية: مكان السكن -->
        <div class="form-step-content" id="step-2">
          <div class="form-grid">
            <div class="input-group">
              <label>المدينة / المنطقة</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-location-dot field-icon"></i>
                <input type="text" id="city" placeholder="ادخل اسم المدينة">
              </div>
            </div>
            <div class="input-group">
              <label>العنوان التفصيلي</label>
              <div class="input-wrapper">
                <i class="fa-solid fa-map-pin field-icon"></i>
                <input type="text" id="address" placeholder="اسم الشارع / الحي">
              </div>
            </div>
          </div>
        </div>

        <!-- الخطوة الثالثة: مراجعة وإرسال -->
        <div class="form-step-content" id="step-3">
          <p style="text-align: center; font-size: 14px; color: #555; margin-bottom: 15px;">
            يرجى مراجعة بياناتك والتأكد من صحتها قبل إتمام عملية التسجيل.
          </p>
        </div>

        <div class="info-note">
          يمكن لأبنائكم استخدام حسابكم لتسجيل الدخول نيابة عنكم
        </div>

        <div class="terms-group">
          <label>
            <input type="checkbox" id="terms" required>
            أوافق على الشروط والأحكام وسياسة الخصوصية
          </label>
        </div>

        <div class="btn-container">
          <button type="button" class="prev-btn" id="prevBtn" onclick="changeStep(-1)">السابق</button>
          <button type="button" class="submit-btn" id="nextBtn" onclick="handleNextClick()">التالي</button>
        </div>

        <div class="login-redirect">
          <span>لديك حساب بالفعل؟</span>
          <a href="{{ route('frontend.login') }}">تسجيل دخول</a>
        </div>
      </form>
    </div>

  </div>

  <script>
    let currentStep = 1;

    // دالة لفتح منتقي التاريخ عند النقر
    function openDatePicker() {
      const dobInput = document.getElementById('dob');
      if (dobInput.showPicker) {
        dobInput.showPicker();
      } else {
        dobInput.focus();
      }
    }

    function togglePasswordVisibility(inputId, icon) {
      const input = document.getElementById(inputId);
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

    function handleNextClick() {
      if (validateCurrentStep()) {
        window.location.href = @json(route('frontend.elderly.housing'));
      }
    }

    function validateCurrentStep() {
      if (currentStep === 1) {
        const fullName = document.getElementById('fullName').value.trim();
        const dob = document.getElementById('dob').value.trim();
        const phone = document.getElementById('phone').value.trim();
        const email = document.getElementById('email').value.trim();
        const pass = document.getElementById('password').value;
        const confirmPass = document.getElementById('confirmPassword').value;
        const terms = document.getElementById('terms').checked;

        if (!fullName || !dob) {
          alert('يرجى ملء جميع الحقول المطلوبة');
          return false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
          alert('يرجى إدخال بريد إلكتروني صحيح يحتوي على @ و اسم النطاق (مثال: example@gmail.com)');
          return false;
        }

        if (phone.length < 9) {
          alert('يرجى إدخال رقم جوال صحيح');
          return false;
        }

        if (!pass) {
          alert('يرجى إدخال كلمة المرور');
          return false;
        }

        if (pass !== confirmPass) {
          alert('كلمتا المرور غير متطابقتين');
          return false;
        }

        if (!terms) {
          alert('يرجى الموافقة على الشروط والأحكام وسياسة الخصوصية للمتابعة');
          return false;
        }
      }
      return true;
    }

    function handleFormSubmit(e) {
      e.preventDefault();
      if (validateCurrentStep()) {
        window.location.href = @json(route('frontend.elderly.housing'));
      }
    }
  </script>
</body>
</html>
