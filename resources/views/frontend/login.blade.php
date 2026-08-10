<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="منصة تجمع بين المتطوعين وكبار السن لتقديم الدعم">
  <title>ehsan</title>
  <link rel="stylesheet" href="{{ asset('assets/css/frontend.css') }}">
  <style>
    /* توسيع الصفحة وإزالة الهوامش الخارجية */
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: #f2ede4;
    }

    /* توسيع الحاوية الرئيسية لتغطي أبعاد أكبر */
    .main-container {
      width: 95vw;          /* توسيع العرض ليشمل 95% من عرض الشاشة */
      max-width: 1400px;    /* رفع الحد الأقصى للعرض */
      min-height: 90vh;     /* توسيع الارتفاع ليشمل 90% من ارتفاع الشاشة */
      display: flex;
      background-color: #ffffff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      margin: 20px auto;
    }

    /* ضبط أقسام النموذج والصورة لتتوزع بشكل متناسق مع الحجم الجديد */
    .form-section, .image-section {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .form-content {
      width: 80%;
      max-width: 480px;
      padding: 40px 20px;
    }

    /* تنسيق خيار ليس لديك حساب */
    .register-option {
      margin-top: 15px;
      text-align: center;
      font-size: 14px;
    }
    .register-link {
      color: #2c3e50;
      font-weight: bold;
      text-decoration: none;
      cursor: pointer;
    }
    .register-link:hover {
      text-decoration: underline;
    }

    /* تمييز زر نوع الحساب المختار */
    .role-btn.active {
      background-color: #2c3e50;
      color: #ffffff;
      border-color: #2c3e50;
    }
  </style>
</head>
<body>

  <div class="main-container">
    <!-- النصف الأيمن: الصورة -->
    <div class="image-section"></div>

    <!-- النصف الأيسر: النموذج -->
    <div class="form-section">
      <div class="form-content">
        <h1>مرحبا بكم في <span class="brand-name">احسان</span></h1>

        <form id="loginForm">
          <div class="input-group">
            <label for="uname">اسم المستخدم</label>
            <input type="text" id="uname" placeholder="ادخل اسم المستخدم" name="uname" required>
          </div>

          <div class="input-group">
            <label for="psw">كلمة المرور</label>
            <input type="password" id="psw" placeholder="ادخل كلمة المرور" name="psw" required>
          </div>

          <div class="options-row">
            <label class="remember-label">
              <input type="checkbox" checked="checked" name="remember"> تذكرني
            </label>
            <a href="#" class="forgot-link">نسيت كلمة المرور؟</a>
          </div>

          <button type="submit" class="submit-btn">تسجيل الدخول</button>

          <div class="user-type-section">
            <p>اختر نوع الحساب</p>
            <div class="role-buttons">
              <button type="button" class="role-btn" data-page="{{ route('frontend.elderly.register') }}">كبير السن</button>
              <button type="button" class="role-btn" data-page="{{ route('frontend.volunteer.register') }}">متطوع</button>
            </div>
          </div>

          <!-- خيار ليس لديك حساب -->
          <div class="register-option">
            <span>ليس لديك حساب؟ </span>
            <a id="registerLink" class="register-link">إنشاء حساب جديد</a>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const roleButtons = document.querySelectorAll(".role-btn");
      const registerLink = document.getElementById("registerLink");
      let selectedPage = null;

      // 1. تحديد نوع الحساب عند الضغط على أزرار "كبير السن" أو "متطوع"
      roleButtons.forEach((button) => {
        button.addEventListener("click", function () {
          roleButtons.forEach((btn) => btn.classList.remove("active"));
          this.classList.add("active");
          selectedPage = this.getAttribute("data-page");
        });
      });

      // 2. عند الضغط على "ليس لديك حساب؟ إنشاء حساب جديد"
      registerLink.addEventListener("click", function (e) {
        e.preventDefault();

        // إذا لم يحدد نوع الحساب أولاً
        if (!selectedPage) {
          alert("يرجى اختيار نوع الحساب أولاً (كبير السن أو متطوع) لإنشاء حساب جديد.");
          return;
        }

        // إذا حدد نوع الحساب، يتم تحويله إلى الصفحة المطلوبة
        window.location.href = selectedPage;
      });
    });
  </script>

</body>
</html>
