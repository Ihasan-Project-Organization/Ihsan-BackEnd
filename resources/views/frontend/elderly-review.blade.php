<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إحسان - مراجعة وإرسال</title>
  
  <!-- استدعاء خط Alexandria -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Alexandria', system-ui, -apple-system, sans-serif;
    }

    body {
      background-color: #f2ede4;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 40px 20px;
    }

    /* تكبير أبعاد الكارت الداخلي */
    .form-card {
      background: #ffffff;
      width: 95vw;
      max-width: 1100px;
      min-height: 85vh;
      border-radius: 24px;
      padding: 50px 60px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    /* --- الهيدر العلوي وشعار إحسان --- */
    .brand-header {
      text-align: center;
      margin-bottom: 30px;
    }

    .brand-title {
      font-size: 38px;
      font-weight: 800;
      color: #9cb08f;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }

    .brand-subtitle {
      font-size: 15px;
      color: #3b5228;
      font-weight: 700;
      margin-top: -2px;
    }

    /* --- شريط الخطوات (Stepper) --- */
    .stepper {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 40px;
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
      font-size: 15px;
      font-weight: 700;
      color: #777;
    }

    .icon-box {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      border: 2px solid #ccc;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 22px;
      background: #fff;
      color: #666;
    }

    /* الخطوة النشطة */
    .step.active .icon-box {
      background-color: #3b5228;
      color: #fff;
      border-color: #3b5228;
    }

    .step.active {
      color: #3b5228;
    }

    .line {
      flex: 1;
      border-top: 2px dashed #ccc;
      margin: 0 20px 28px;
    }

    /* --- كروت عرض البيانات للمراجعة --- */
    .review-card {
      border: 2px solid #a3b293;
      border-radius: 18px;
      padding: 28px 35px;
      margin-bottom: 25px;
      background-color: #fff;
    }

    .review-card-title {
      font-size: 20px;
      font-weight: 800;
      color: #000;
      text-align: right;
      margin-bottom: 20px;
    }

    .info-table {
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .info-label {
      font-size: 16px;
      font-weight: 700;
      color: #000;
    }

    .info-value {
      font-size: 16px;
      font-weight: 700;
      color: #3b5228;
      text-align: left;
    }

    /* --- شريط الموافقة والشروط --- */
    .terms-banner {
      background-color: #e4ebd9;
      border-radius: 16px;
      padding: 16px 24px;
      text-align: center;
      color: #3b5228;
      font-size: 15px;
      font-weight: 700;
      margin: 20px 0 30px;
    }

    /* --- أزرار التحكم --- */
    .action-buttons {
      display: flex;
      gap: 20px;
    }

    .btn-submit {
      flex: 1;
      padding: 16px;
      background-color: #2b3d1b;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 18px;
      font-weight: 700;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .btn-prev {
      flex: 1;
      padding: 16px;
      background-color: #fff;
      color: #3b5228;
      border: 2px solid #3b5228;
      border-radius: 10px;
      font-size: 18px;
      font-weight: 700;
      cursor: pointer;
      text-align: center;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="form-card">

    <!-- الشعار -->
    <div class="brand-header">
      <div class="brand-title">
        إحسان <i class="fa-solid fa-hand-holding-heart"></i>
      </div>
      <div class="brand-subtitle">منصة ربط كبار السن بمقدمي الخدمة</div>
    </div>
    
    <!-- شريط الخطوات -->
    <div class="stepper">
      <div class="step">
        <div class="icon-box"><i class="fa-solid fa-user"></i></div>
        <span>المعلومات الشخصية</span>
      </div>
      <div class="line"></div>
      <div class="step">
        <div class="icon-box"><i class="fa-solid fa-house"></i></div>
        <span>مكان السكن</span>
      </div>
      <div class="line"></div>
      <div class="step active">
        <div class="icon-box"><i class="fa-solid fa-upload"></i></div>
        <span>مراجعة وإرسال</span>
      </div>
    </div>

    <!-- كارت المعلومات الشخصية الديناميكي -->
    <div class="review-card">
      <div class="review-card-title">المعلومات الشخصية</div>
      <div class="info-table">
        <div class="info-row">
          <span class="info-label">الاسم الكامل</span>
          <span class="info-value" id="val-fullName">غير مدخل</span>
        </div>
        <div class="info-row">
          <span class="info-label">تاريخ الميلاد</span>
          <span class="info-value" id="val-dob">غير مدخل</span>
        </div>
        <div class="info-row">
          <span class="info-label">رقم الجوال</span>
          <span class="info-value" id="val-phone">غير مدخل</span>
        </div>
        <div class="info-row">
          <span class="info-label">البريد الالكتروني</span>
          <span class="info-value" id="val-email">غير مدخل</span>
        </div>
      </div>
    </div>

    <!-- كارت تفاصيل السكن الديناميكي -->
    <div class="review-card">
      <div class="review-card-title">تفاصيل السكن</div>
      <div class="info-table">
        <div class="info-row">
          <span class="info-label">المدينة</span>
          <span class="info-value" id="val-city">غير مدخل</span>
        </div>
        <div class="info-row">
          <span class="info-label">الحي/ المنطقة</span>
          <span class="info-value" id="val-district">غير مدخل</span>
        </div>
        <div class="info-row">
          <span class="info-label">العنوان التفصيلي</span>
          <span class="info-value" id="val-address">غير مدخل</span>
        </div>
        <div class="info-row">
          <span class="info-label">نوع السكن</span>
          <span class="info-value" id="val-housingType">غير مدخل</span>
        </div>
        <div class="info-row">
          <span class="info-label">السكن مع</span>
          <span class="info-value" id="val-livingWith">غير مدخل</span>
        </div>
        <div class="info-row">
          <span class="info-label">معلومات إضافية</span>
          <span class="info-value" id="val-extraInfo">لا يوجد</span>
        </div>
      </div>
    </div>

    <!-- ملاحظة الشروط -->
    <div class="terms-banner">
      بإرسال طلبك فإنك توافق على الشروط والأحكام وسياسة الخصوصية الخاصة بمنصة الاحسان
    </div>

    <!-- الأزرار -->
    <div class="action-buttons">
      <button type="button" class="btn-submit" onclick="submitForm()">
        <i class="fa-regular fa-paper-plane"></i> إرسال الطلب
      </button>
      <button type="button" class="btn-prev" onclick="window.location.href={{ Js::from(route('frontend.elderly.housing')) }}">السابق</button>
    </div>

  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // 1. جلب بيانات الصفحة الأولى
      document.getElementById("val-fullName").innerText = localStorage.getItem("fullName") || "غير مدخل";
      document.getElementById("val-dob").innerText = localStorage.getItem("dob") || "غير مدخل";
      document.getElementById("val-phone").innerText = localStorage.getItem("phone") || "غير مدخل";
      document.getElementById("val-email").innerText = localStorage.getItem("email") || "غير مدخل";

      // 2. جلب بيانات الصفحة الثانية
      document.getElementById("val-city").innerText = localStorage.getItem("city") || "غير مدخل";
      document.getElementById("val-district").innerText = localStorage.getItem("district") || "غير مدخل";
      document.getElementById("val-address").innerText = localStorage.getItem("address") || "غير مدخل";
      document.getElementById("val-housingType").innerText = localStorage.getItem("housingType") || "غير مدخل";
      document.getElementById("val-livingWith").innerText = localStorage.getItem("livingWith") || "غير مدخل";
      document.getElementById("val-extraInfo").innerText = localStorage.getItem("extraInfo") || "لا يوجد";
    });

    function submitForm() {
      const fullPayload = {
        fullName: localStorage.getItem("fullName"),
        dob: localStorage.getItem("dob"),
        phone: localStorage.getItem("phone"),
        email: localStorage.getItem("email"),
        city: localStorage.getItem("city"),
        district: localStorage.getItem("district"),
        address: localStorage.getItem("address"),
        housingType: localStorage.getItem("housingType"),
        livingWith: localStorage.getItem("livingWith"),
        extraInfo: localStorage.getItem("extraInfo")
      };

      console.log("البيانات الجاهزة للإرسال النهائي:", fullPayload);

      alert("تم إرسال الطلب بنجاح!");
      localStorage.clear();
      window.location.href = @json(route('frontend.login'));
    }
  </script>

</body>
</html>
