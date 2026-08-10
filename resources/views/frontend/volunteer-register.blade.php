<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إحسان - إنشاء حساب مقدم خدمة</title>
  <!-- استيراد خط Alexandria من Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      /* تطبيق خط Alexandria على كافة عناصر الصفحة */
      font-family: 'Alexandria', sans-serif;
    }

    body {
      background-color: #f2ede4;
      display: flex;
      justify-content: center;
      padding: 40px 20px;
    }

    .page-container {
      width: 100%;
      max-width: 1050px;
      position: relative;
    }

    /* =========================================
       قسم الهيدر العلوي
       ========================================= */
    .hero-header {
      position: relative;
      width: 100%;
      height: 270px;
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
      width: 230px;
      height: 245px;
      background: #ffffff;
      border-radius: 45px 45px 0 0;
      padding: 9px 9px 0 9px;
      box-shadow: 0 -4px 15px rgba(0,0,0,0.04);
      z-index: 5;
    }

    .volunteer-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 38px 38px 0 0;
    }

    .header-center-content {
      position: relative;
      z-index: 5;
      margin-right: 310px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 16px;
    }

    .top-action-row {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .avatar-icon {
      width: 50px;
      height: 50px;
      background-color: #d6ded0;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: #3b5228;
      flex-shrink: 0;
    }

    .back-btn {
      color: #2b3a1d;
      text-decoration: none;
      font-weight: 700;
      font-size: 16px;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .title-block-text h2 {
      font-size: 29px;
      color: #2b3a1d;
      font-weight: 800;
      line-height: 1.2;
    }

    .title-block-text p {
      font-size: 15px;
      color: #666;
      margin-top: 6px;
    }

    /* =========================================
       كارت النموذج والحقول
       ========================================= */
    .form-card {
      background: #ffffff;
      border-radius: 28px;
      padding: 50px 60px 45px;
      margin-top: -12px;
      position: relative;
      z-index: 10;
      box-shadow: 0 12px 35px rgba(0,0,0,0.05);
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 25px 35px;
    }

    .input-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .input-group label {
      font-size: 15px;
      font-weight: 700;
      color: #333;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-wrapper input {
      width: 100%;
      padding: 16px 50px 16px 18px;
      border: 1.8px solid #3b5228;
      border-radius: 15px;
      font-size: 15px;
      outline: none;
      font-family: 'Alexandria', sans-serif;
    }

    .input-wrapper input[type="date"] {
      cursor: pointer;
    }

    .input-wrapper i.field-icon {
      position: absolute;
      right: 18px;
      color: #3b5228;
      font-size: 18px;
    }

    .toggle-password {
      position: absolute;
      left: 18px;
      right: auto !important;
      cursor: pointer;
      color: #777;
      font-size: 18px;
    }

    .terms-group {
      display: flex;
      justify-content: center;
      margin: 35px 0 28px;
      font-size: 15px;
      font-weight: 600;
      color: #444;
    }

    .terms-group input {
      margin-left: 10px;
      accent-color: #3b5228;
      width: 20px;
      height: 20px;
      cursor: pointer;
    }

    .submit-btn {
      width: 100%;
      padding: 16px;
      background-color: #3b5228;
      color: white;
      border: none;
      border-radius: 15px;
      font-size: 17px;
      font-weight: 700;
      cursor: pointer;
      transition: background-color 0.2s;
      font-family: 'Alexandria', sans-serif;
    }

    .submit-btn:hover {
      background-color: #2e411f;
    }

    .login-redirect {
      text-align: center;
      margin-top: 22px;
      font-size: 15px;
    }

    .login-btn {
      color: #3b5228;
      font-weight: 700;
      margin-right: 5px;
      cursor: pointer;
      text-decoration: none;
    }

    @media (max-width: 850px) {
      .hero-header {
        height: auto;
        padding: 25px;
        flex-direction: column;
        align-items: center;
      }

      .volunteer-card-wrapper {
        position: relative;
        right: 0;
        height: 180px;
        width: 170px;
        margin-bottom: 15px;
      }

      .header-center-content {
        margin-right: 0;
        align-items: center;
        text-align: center;
      }

      .form-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <div class="page-container">
    
    <div class="hero-header">
      <img src="{{ asset('assets/img/header.jpeg') }}" alt="Header Background" class="header-bg">
      
      <div class="volunteer-card-wrapper">
        <img src="{{ asset('assets/img/vol.jpeg') }}" alt="Volunteer" class="volunteer-img">
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
          <h2>إنشاء حساب مقدم خدمة</h2>
          <p>أنشئ حسابك الآن لتتمكن من تقديم الخدمات بسهولة</p>
        </div>
      </div>
    </div>

    <div class="form-card">
      
      <form id="registrationForm" onsubmit="handleNextStep(event)">
        <div class="form-grid">
          <div class="input-group">
            <label>الاسم بالكامل</label>
            <div class="input-wrapper">
              <i class="fa-regular fa-pen-to-square field-icon"></i>
              <input type="text" placeholder="ادخل اسمك كامل" required>
            </div>
          </div>

          <div class="input-group">
            <label>تاريخ الميلاد</label>
            <div class="input-wrapper">
              <i class="fa-regular fa-calendar field-icon"></i>
              <input type="date" required>
            </div>
          </div>

          <div class="input-group">
            <label>رقم الهوية</label>
            <div class="input-wrapper">
              <i class="fa-regular fa-id-card field-icon"></i>
              <input type="text" placeholder="ادخل رقم الهوية" required>
            </div>
          </div>

          <div class="input-group">
            <label>رقم الجوال</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-phone field-icon"></i>
              <input type="tel" placeholder="059XXXXXXX" required>
            </div>
          </div>

          <div class="input-group">
            <label>كلمة المرور</label>
            <div class="input-wrapper">
              <i class="fa-solid fa-lock field-icon"></i>
              <input type="password" id="passwordField" placeholder="ادخل كلمة المرور" required>
              <i class="fa-regular fa-eye toggle-password" id="togglePassword" onclick="togglePasswordVisibility()"></i>
            </div>
          </div>

          <div class="input-group">
            <label>البريد الإلكتروني</label>
            <div class="input-wrapper">
              <i class="fa-regular fa-envelope field-icon"></i>
              <input type="email" placeholder="ادخل بريدك الإلكتروني" required>
            </div>
          </div>
        </div>

        <div class="terms-group">
          <label>
            <input type="checkbox" required>
            أوافق على الشروط والأحكام وسياسة الخصوصية
          </label>
        </div>

        <button type="submit" class="submit-btn">التالي</button>

        <div class="login-redirect">
          <span>لديك حساب بالفعل؟</span>
          <a href="{{ route('frontend.login') }}" class="login-btn">تسجيل دخول</a>
        </div>
      </form>

    </div>

  </div>

  <script>
    function togglePasswordVisibility() {
      const passwordField = document.getElementById('passwordField');
      const toggleIcon = document.getElementById('togglePassword');
      
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
      } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
      }
    }

    function handleNextStep(event) {
      event.preventDefault();
      window.location.href = @json(route('frontend.volunteer.documents'));
    }
  </script>
</body>
</html>
