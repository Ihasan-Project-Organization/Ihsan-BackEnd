<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="منصة إحسان لخدمة كبار السن وربطهم بالمتطوعين">
  <title>تسجيل الدخول | إحسان</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f2ede4;
      font-family: 'Alexandria', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: #333333;
    }

    .main-container {
      width: 95vw;
      max-width: 1200px;
      min-height: 85vh;
      display: flex;
      background-color: #ffffff;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      margin: 20px auto;
    }

    /* قسم الصورة */
    .image-section {
      flex: 1.1;
      background-image: linear-gradient(rgba(49, 66, 30, 0.2), rgba(49, 66, 30, 0.3)), url('{{ asset('assets/img/hero-image.jpeg') }}');
      background-size: cover;
      background-position: center 30%;
      background-repeat: no-repeat;
      display: flex;
      align-items: flex-end;
      padding: 40px;
      position: relative;
    }

    .image-overlay-text {
      background: rgba(49, 66, 30, 0.85);
      backdrop-filter: blur(8px);
      padding: 24px 30px;
      border-radius: 18px;
      color: #ffffff;
      max-width: 460px;
    }

    .image-overlay-text h2 {
      font-size: 22px;
      font-weight: 800;
      margin: 0 0 8px;
      color: #ffffff;
    }

    .image-overlay-text p {
      font-size: 13px;
      line-height: 1.6;
      margin: 0;
      color: #e6edd9;
    }

    /* قسم النموذج */
    .form-section {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px 30px;
      background-color: #ffffff;
    }

    .form-content {
      width: 100%;
      max-width: 420px;
    }

    h1 {
      font-size: 26px;
      font-weight: 900;
      color: #31421e;
      margin-bottom: 24px;
      text-align: center;
    }

    .brand-name {
      color: #718256;
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
      padding: 12px 18px;
      border: 1.5px solid #d2d6dc;
      border-radius: 25px;
      outline: none;
      font-size: 14px;
      font-family: inherit;
      transition: border-color 0.2s, box-shadow 0.2s;
      background-color: #fafaf9;
    }

    input[type="email"]:focus,
    input[type="password"]:focus {
      border-color: #718256;
      background-color: #ffffff;
      box-shadow: 0 0 0 3px rgba(113, 130, 86, 0.15);
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
      background-color: #31421e;
      color: white;
      border: none;
      border-radius: 25px;
      font-size: 15px;
      font-weight: 800;
      font-family: inherit;
      cursor: pointer;
      transition: background-color 0.2s, transform 0.1s;
      box-shadow: 0 4px 14px rgba(49, 66, 30, 0.25);
    }

    .submit-btn:hover {
      background-color: #52643a;
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
        min-height: 220px;
        flex: none;
        padding: 20px;
      }

      .image-overlay-text {
        display: none;
      }

      .form-section {
        padding: 30px 20px;
      }
    }
  </style>
</head>
<body>

  <div class="main-container">
    <!-- النصف الأيمن: الصورة -->
    <div class="image-section" role="img" aria-label="منصة إحسان">
      <div class="image-overlay-text">
        <h2>معًا لرعاية كبار السن</h2>
        <p>نصل كبار السن بالمتطوعين الموثوقين والجمعيات الخيرية لتقديم الدعم والمرافقة وقضاء الحوائج بكل محبة وإحسان.</p>
      </div>
    </div>

    <!-- النصف الأيسر: النموذج -->
    <div class="form-section">
      <div class="form-content">
        <h1>مرحبا بكم في <span class="brand-name">إحسان</span></h1>

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

        @if (app()->environment('local'))
        <!-- ======================================================== -->
        <!-- [مؤقت للاختبار فقط] أزرار التعبئة السريعة لحسابات النظام -->
        <!-- ======================================================== -->
        <div style="background: #f8fafc; border: 1.5px dashed #cbd5e1; border-radius: 16px; padding: 14px; margin-bottom: 20px;">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px;">
            <div style="display: flex; align-items: center; gap: 6px; font-weight: 800; font-size: 12px; color: #1e293b;">
              <span style="font-size: 14px;">⚡</span>
              <span>حسابات تجريبية للاختبار السريع:</span>
            </div>
            <span style="font-size: 10px; background: #e2e8f0; color: #475569; padding: 2px 8px; border-radius: 10px; font-weight: 700;">مؤقت للاختبار</span>
          </div>

          <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;">
            <!-- 1. مدير أعلى -->
            <button type="button" class="quick-fill-btn" onclick="fillCredentials('superadmin@ihsan.com', 'password', 'مدير النظام الأعلى (Super Admin)')"
              style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; padding: 8px 10px; text-align: right; cursor: pointer; transition: all 0.15s; font-family: inherit;">
              <div style="font-weight: 800; font-size: 11.5px; color: #92400e; display: flex; align-items: center; gap: 5px;">
                <span>👑</span>
                <span>مدير أعلى (Super)</span>
              </div>
              <div style="font-size: 9.5px; color: #b45309; margin-top: 2px; direction: ltr; text-align: right;">superadmin@ihsan.com</div>
            </button>

            <!-- 2. مدير نظام -->
            <button type="button" class="quick-fill-btn" onclick="fillCredentials('admin@ihsan.com', 'password', 'مدير نظام (Admin)')"
              style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 8px 10px; text-align: right; cursor: pointer; transition: all 0.15s; font-family: inherit;">
              <div style="font-weight: 800; font-size: 11.5px; color: #1e40af; display: flex; align-items: center; gap: 5px;">
                <span>🛡️</span>
                <span>مدير نظام (Admin)</span>
              </div>
              <div style="font-size: 9.5px; color: #2563eb; margin-top: 2px; direction: ltr; text-align: right;">admin@ihsan.com</div>
            </button>

            <!-- 3. مقدم خدمة (متطوع) -->
            <button type="button" class="quick-fill-btn" onclick="fillCredentials('mohammed@ihsan.com', 'password', 'مقدم الخدمة (متطوع)')"
              style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 8px 10px; text-align: right; cursor: pointer; transition: all 0.15s; font-family: inherit;">
              <div style="font-weight: 800; font-size: 11.5px; color: #166534; display: flex; align-items: center; gap: 5px;">
                <span>🤝</span>
                <span>مقدم خدمة (متطوع)</span>
              </div>
              <div style="font-size: 9.5px; color: #15803d; margin-top: 2px; direction: ltr; text-align: right;">mohammed@ihsan.com</div>
            </button>

            <!-- 4. كبير سن (مستفيد) -->
            <button type="button" class="quick-fill-btn" onclick="fillCredentials('elderly@ihsan.com', 'password', 'كبير سن (مستفيد)')"
              style="background: #faf5ff; border: 1px solid #e9d5ff; border-radius: 10px; padding: 8px 10px; text-align: right; cursor: pointer; transition: all 0.15s; font-family: inherit;">
              <div style="font-weight: 800; font-size: 11.5px; color: #6b21a8; display: flex; align-items: center; gap: 5px;">
                <span>🧓</span>
                <span>كبير سن (مستفيد)</span>
              </div>
              <div style="font-size: 9.5px; color: #7e22ce; margin-top: 2px; direction: ltr; text-align: right;">elderly@ihsan.com</div>
            </button>

            <!-- 5. بانتظار الاعتماد -->
            <button type="button" class="quick-fill-btn" onclick="fillCredentials('pending@ihsan.com', 'password', 'حساب بانتظار الاعتماد')"
              style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 10px; padding: 8px 10px; text-align: right; cursor: pointer; transition: all 0.15s; font-family: inherit;">
              <div style="font-weight: 800; font-size: 11.5px; color: #9a3412; display: flex; align-items: center; gap: 5px;">
                <span>⏳</span>
                <span>بانتظار الاعتماد</span>
              </div>
              <div style="font-size: 9.5px; color: #c2410c; margin-top: 2px; direction: ltr; text-align: right;">pending@ihsan.com</div>
            </button>

            <!-- 6. حساب موقوف -->
            <button type="button" class="quick-fill-btn" onclick="fillCredentials('suspended@ihsan.com', 'password', 'حساب موقوف')"
              style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 8px 10px; text-align: right; cursor: pointer; transition: all 0.15s; font-family: inherit;">
              <div style="font-weight: 800; font-size: 11.5px; color: #991b1b; display: flex; align-items: center; gap: 5px;">
                <span>🚫</span>
                <span>حساب موقوف</span>
              </div>
              <div style="font-size: 9.5px; color: #b91c1c; margin-top: 2px; direction: ltr; text-align: right;">suspended@ihsan.com</div>
            </button>
          </div>

          <div id="quickFillNotice" style="display:none; margin-top: 10px; font-size: 11px; font-weight: 700; color: #065f46; text-align: center; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 6px 10px;">
            ✓ تم تعبئة البيانات بنجاح! يمكنك الآن الضغط على تسجيل الدخول.
          </div>
        </div>
        <!-- ======================================================== -->
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
              <a href="{{ route('frontend.elderly.register') }}" class="role-btn">حساب كبير السن</a>
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

  @if (app()->environment('local'))
  <script>
    function fillCredentials(email, password, roleName) {
      const emailInput = document.getElementById('email');
      const passwordInput = document.getElementById('password');
      const notice = document.getElementById('quickFillNotice');

      if (emailInput && passwordInput) {
        emailInput.value = email;
        passwordInput.value = password;

        emailInput.style.borderColor = '#10b981';
        passwordInput.style.borderColor = '#10b981';
        setTimeout(() => {
          emailInput.style.borderColor = '';
          passwordInput.style.borderColor = '';
        }, 1500);

        if (notice) {
          notice.innerHTML = '✓ تم اختيار حساب: <strong>' + roleName + '</strong>. اضغط الآن على زر تسجيل الدخول أدناه.';
          notice.style.display = 'block';
        }
      }
    }
  </script>
  @endif

</body>
</html>
