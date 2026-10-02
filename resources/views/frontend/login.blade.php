<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="منصة أنيس لربط طالبي المساعدة بمقدمي الخدمة الموثوقين">
  <title>تسجيل الدخول | أنيس</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: radial-gradient(circle at 10% 10%, rgba(223, 242, 232, .9), transparent 30%), #f7f1e8;
      font-family: 'Alexandria', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #153f36;
    }

    .main-container {
      width: min(94vw, 1180px);
      min-height: min(720px, calc(100vh - 48px));
      display: flex;
      background-color: #ffffff;
      border: 1px solid #dfe9e4;
      border-radius: 32px;
      overflow: hidden;
      box-shadow: 0 24px 70px rgba(21, 63, 54, .12);
      margin: 24px auto;
    }

    /* قسم الصورة */
    .image-section {
      flex: 1.1;
      background-image: linear-gradient(180deg, rgba(21, 63, 54, .02) 30%, rgba(21, 63, 54, .82) 100%), url('{{ asset('assets/img/assistance-seeker-register.jpg') }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      display: flex;
      align-items: flex-end;
      padding: 32px;
      position: relative;
    }

    .image-overlay-text {
      background: rgba(21, 63, 54, .78);
      backdrop-filter: blur(14px);
      padding: 22px 24px;
      border: 1px solid rgba(255,255,255,.15);
      border-radius: 20px;
      color: #ffffff;
      max-width: 460px;
    }

    .image-overlay-text h2 {
      font-size: 24px;
      font-weight: 800;
      margin: 0 0 8px;
      color: #ffffff;
    }

    .image-overlay-text p {
      font-size: 13px;
      line-height: 1.6;
      margin: 0;
      color: rgba(255,255,255,.76);
    }

    /* قسم النموذج */
    .form-section {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 34px 38px;
      background-color: #ffffff;
    }

    .form-content {
      width: 100%;
      max-width: 430px;
    }

    .form-brand { display: flex; justify-content: center; margin-bottom: 6px; }
    .form-brand img { width: 92px; height: 72px; object-fit: contain; }

    h1 {
      font-size: 28px;
      font-weight: 900;
      color: #153f36;
      margin: 0 0 6px;
      text-align: center;
    }

    .brand-name {
      color: #e9785d;
    }

    .welcome-copy {
      margin: 0 0 22px;
      text-align: center;
      color: #64736e;
      font-size: 13px;
      font-weight: 500;
      line-height: 1.8;
    }

    .input-group {
      margin-bottom: 18px;
    }

    label {
      display: block;
      font-weight: 700;
      margin-bottom: 8px;
      font-size: 13px;
      color: #2d3748;
    }

    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 14px 16px;
      border: 1.5px solid #dfe9e4;
      border-radius: 14px;
      outline: none;
      font-size: 14px;
      font-family: inherit;
      transition: border-color 0.2s, box-shadow 0.2s;
      background-color: #f8fbf9;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #24584c;
      background-color: #ffffff;
      box-shadow: 0 0 0 4px rgba(36, 88, 76, .10);
    }

    .options-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 22px;
      font-size: 13px;
    }

    .remember-label {
      display: flex;
      align-items: center;
      gap: 7px;
      margin-bottom: 0;
      font-weight: normal;
      cursor: pointer;
      color: #4a5568;
    }

    .forgot-link {
      color: #52643a;
      font-weight: 700;
      text-decoration: none;
      transition: color 0.2s;
    }

    .forgot-link:hover {
      text-decoration: underline;
      color: #31421e;
    }

    .submit-btn {
      width: 100%;
      padding: 13px;
      background-color: #153f36;
      color: white;
      border: none;
      border-radius: 14px;
      font-size: 15px;
      font-weight: 800;
      font-family: inherit;
      cursor: pointer;
      transition: background-color 0.2s, transform 0.1s;
      box-shadow: 0 10px 24px rgba(21, 63, 54, .20);
    }

    .submit-btn:hover {
      background-color: #24584c;
      transform: translateY(-1px);
    }

    .submit-btn:active {
      transform: scale(0.99);
    }

    .user-type-section {
      margin-top: 24px;
      text-align: center;
      border-top: 1px solid #edf2f7;
      padding-top: 20px;
    }

    .user-type-section p {
      font-size: 13px;
      color: #718096;
      margin-bottom: 12px;
      font-weight: 600;
    }

    .role-buttons {
      display: flex;
      gap: 12px;
    }

    .role-btn {
      flex: 1;
      padding: 10px;
      border: 1.5px solid #718256;
      border-radius: 12px;
      background: white;
      color: #52643a;
      font-weight: 700;
      cursor: pointer;
      font-size: 13px;
      font-family: inherit;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s;
    }

    .role-btn:hover {
      background-color: #eef2e8;
      border-color: #31421e;
      color: #31421e;
    }

    .quick-fill-card {
      transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .quick-fill-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .quick-panel { margin-bottom: 20px; overflow: hidden; border: 1px dashed #b8cac2; border-radius: 14px; background: #f8fbf9; }
    .quick-panel summary { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px 14px; color: #24584c; font-size: 12px; font-weight: 800; cursor: pointer; list-style: none; }
    .quick-panel summary::-webkit-details-marker { display: none; }
    .quick-panel summary::after { content: '+'; font-size: 18px; line-height: 1; }
    .quick-panel[open] summary::after { content: '−'; }
    .quick-panel[open] summary { border-bottom: 1px solid #dfe9e4; }
    .quick-grid { padding: 12px; }

    .register-option {
      margin-top: 16px;
      text-align: center;
      font-size: 13px;
      color: #718096;
    }

    .register-link {
      color: #31421e;
      font-weight: 800;
      text-decoration: none;
      cursor: pointer;
    }

    .register-link:hover {
      text-decoration: underline;
    }

    /* رسائل التنبيه والخطأ */
    .alert-box {
      border-radius: 14px;
      padding: 12px 16px;
      margin-bottom: 18px;
      font-size: 13px;
      line-height: 1.5;
    }

    .alert-danger {
      background-color: #fef2f2;
      border: 1px solid #fecaca;
      color: #b91c1c;
    }

    .alert-warning {
      background-color: #fffbeb;
      border: 1px solid #fef3c7;
      color: #92400e;
    }

    .alert-success {
      background-color: #ecfdf5;
      border: 1px solid #a7f3d0;
      color: #065f46;
    }

    @media (max-width: 900px) {
      .main-container {
        flex-direction: column;
        width: 100vw;
        min-height: 100vh;
        margin: 0;
        border-radius: 0;
      }

      .image-section {
        min-height: 260px;
        flex: none;
        padding: 20px;
      }

      .image-overlay-text {
        display: none;
      }

      .form-section {
        padding: 28px 20px 36px;
      }

      .form-brand img { width: 82px; height: 62px; }
      h1 { font-size: 24px; }
    }
  </style>
</head>
<body>

  <div class="main-container">
    <!-- النصف الأيمن: الصورة -->
    <div class="image-section" role="img" aria-label="منصة أنيس">
      <div class="image-overlay-text">
        <h2>المساعدة أقرب مما تتخيّل</h2>
        <p>أنيس يربط كبار السن وذوي الإعاقة ومبتوري الأطراف بمقدمي خدمة موثوقين، باهتمام يحفظ الراحة والكرامة.</p>
      </div>
    </div>

    <!-- النصف الأيسر: النموذج -->
    <div class="form-section">
      <div class="form-content">
        <a href="{{ url('/') }}" class="form-brand" aria-label="العودة إلى صفحة أنيس الرئيسية">
          <img src="{{ asset('assets/img/anees-logo.png') }}" alt="شعار منصة أنيس">
        </a>
        <h1>مرحبًا بعودتك إلى <span class="brand-name">أنيس</span></h1>
        <p class="welcome-copy">سجّل دخولك للوصول إلى خدماتك ومتابعة طلباتك بسهولة.</p>

        {{-- تنبيهات الحالة مثل تأكيد البريد أو إعادة تعيين كلمة المرور --}}
        @if (session('status'))
          <div class="alert-box alert-success" role="status">
            {{ session('status') }}
          </div>
        @endif

        {{-- تنبيه خاص إذا كان الحساب مرفوضًا ومعه سبب الرفض --}}
        @if ($errors->has('rejection_reason'))
          <div class="alert-box alert-danger" role="alert">
            <strong>تم رفض الحساب:</strong> {{ $errors->first('rejection_reason') }}
          </div>
        @endif

        {{-- تنبيه خطأ البريد الإلكتروني أو بيانات الدخول --}}
        @if ($errors->has('email') && !$errors->has('rejection_reason'))
          <div class="alert-box alert-danger" role="alert">
            {{ $errors->first('email') }}
          </div>
        @endif

        {{-- تنبيه أي أخطاء أخرى عامة --}}
        @if ($errors->has('password'))
          <div class="alert-box alert-danger" role="alert">
            {{ $errors->first('password') }}
          </div>
        @endif



        <form method="POST" action="{{ route('login') }}" id="loginForm">
          @csrf

          <div class="input-group">
            <label for="email">البريد الإلكتروني</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="ادخل البريد الإلكتروني" required autofocus autocomplete="username">
          </div>

          <div class="input-group">
            <label for="password">كلمة المرور</label>
            <input type="password" id="password" name="password" placeholder="ادخل كلمة المرور" required autocomplete="current-password">
          </div>

          <div class="options-row">
            <label class="remember-label">
              <input type="checkbox" name="remember" @checked(old('remember'))> تذكرني
            </label>
            <a href="{{ route('password.request') }}" class="forgot-link">نسيت كلمة المرور؟</a>
          </div>

          <button type="submit" class="submit-btn">تسجيل الدخول</button>

          <div class="user-type-section">
            <p>ليس لديك حساب؟ اختر نوع الحساب</p>
            <div class="role-buttons">
              <a href="{{ route('frontend.elderly.register') }}" class="role-btn">حساب طالب مساعدة</a>
              <a href="{{ route('frontend.volunteer.register') }}" class="role-btn">حساب متطوع</a>
            </div>
          </div>

          <div class="register-option">
            <span>أو تفضل بزيارة </span>
            <a href="{{ route('register.choose') }}" class="register-link">صفحة اختيار التسجيل</a>
          </div>
        </form>
      </div>
    </div>
  </div>



</body>
</html>
