<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إحسان - الوثائق المطلوبة</title>
  <!-- استيراد خط Alexandria من Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Alexandria', sans-serif;
    }

    body {
      background-color: #fcfcfc;
      display: flex;
      justify-content: center;
      padding: 40px 20px;
    }

    .page-container {
      width: 100%;
      max-width: 950px;
      position: relative;
    }

    /* =========================================
       الهيدر العلوي والشعار (الشعار فوق الكلام)
       ========================================= */
    .top-header {
      text-align: center;
      margin-bottom: 45px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .logo-graphic {
      font-size: 38px;
      color: #8c967e;
      margin-bottom: 8px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .logo-text-group {
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .logo-main-text {
      font-size: 34px;
      color: #31421e;
      font-weight: 800;
      line-height: 1.1;
    }

    .logo-subtext {
      font-size: 13px;
      color: #555;
      font-weight: 500;
      margin-top: 2px;
    }

    /* =========================================
       شريط الخطوات (Stepper)
       ========================================= */
    .stepper-container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: relative;
      margin: 0 auto 50px auto;
      max-width: 750px;
      padding: 0 30px;
    }

    /* الخط المتقطع بين الخطوات */
    .stepper-container::before {
      content: '';
      position: absolute;
      top: 26px;
      left: 15%;
      right: 15%;
      height: 2px;
      border-top: 2px dashed #31421e;
      z-index: 1;
    }

    .step-item {
      display: flex;
      flex-direction: column;
      align-items: center;
      position: relative;
      z-index: 2;
    }

    .step-circle {
      width: 55px;
      height: 55px;
      background-color: #ffffff;
      border: 2px solid #31421e;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      color: #31421e;
      margin-bottom: 10px;
    }

    /* الخطوة المكتملة (مظللة باللون الأخضر مع أيقونة صح أو لونها الكامل) */
    .step-item.completed .step-circle {
      background-color: #31421e;
      color: #ffffff;
    }

    /* الخطوة الحالية المفعلة */
    .step-item.active .step-circle {
      background-color: #31421e;
      color: #ffffff;
    }

    .step-text {
      font-size: 14px;
      font-weight: 700;
      color: #31421e;
      white-space: nowrap;
    }

    /* =========================================
       بطاقات رفع المستندات
       ========================================= */
    .upload-card {
      background: #ffffff;
      border: 1.8px solid #dcd6cd;
      border-radius: 18px;
      padding: 22px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .card-right-side {
      display: flex;
      align-items: center;
      gap: 22px;
    }

    .doc-illustration {
      width: 65px;
      height: 55px;
      border: 2px solid #31421e;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #31421e;
      font-size: 26px;
      flex-shrink: 0;
    }

    .doc-info h3 {
      font-size: 18px;
      font-weight: 700;
      color: #1a1a1a;
      margin-bottom: 6px;
    }

    .doc-info p {
      font-size: 13px;
      color: #666;
      font-weight: 500;
    }

    .card-left-side {
      display: flex;
      flex-direction: column;
      align-items: center;
      flex-shrink: 0;
    }

    .upload-btn {
      background-color: #31421e;
      color: #ffffff;
      border: none;
      border-radius: 10px;
      padding: 10px 22px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-family: 'Alexandria', sans-serif;
      transition: background-color 0.2s;
    }

    .upload-btn:hover {
      background-color: #243215;
    }

    .file-hint {
      font-size: 11px;
      color: #777;
      margin-top: 6px;
      font-weight: 600;
    }

    /* =========================================
       صندوق الخصوصية والأمان
       ========================================= */
    .security-banner {
      background-color: #e3e7d5;
      border-radius: 16px;
      padding: 18px 25px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 30px 0 35px 0;
    }

    .security-content {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .security-content i {
      font-size: 28px;
      color: #31421e;
    }

    .security-text h4 {
      font-size: 16px;
      font-weight: 700;
      color: #31421e;
      margin-bottom: 3px;
    }

    .security-text p {
      font-size: 13px;
      color: #333;
      font-weight: 500;
    }

    /* =========================================
       أزرار التنقل السفلي (السابق / التالي)
       ========================================= */
    .navigation-row {
      display: flex;
      gap: 20px;
    }

    .nav-btn {
      flex: 1;
      padding: 15px;
      border-radius: 14px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      font-family: 'Alexandria', sans-serif;
      text-align: center;
      transition: background-color 0.2s;
    }

    .btn-prev {
      background-color: #ffffff;
      border: 2px solid #31421e;
      color: #31421e;
    }

    .btn-prev:hover {
      background-color: #f7f9f4;
    }

    .btn-next {
      background-color: #31421e;
      border: none;
      color: #ffffff;
    }

    .btn-next:hover {
      background-color: #243215;
    }

    /* إخفاء حقول الملفات الفعلية */
    input[type="file"] {
      display: none;
    }

    /* التجاوب مع الشاشات الصغيرة */
    @media (max-width: 768px) {
      .upload-card {
        flex-direction: column;
        align-items: flex-start;
        gap: 20px;
      }
      .card-left-side {
        width: 100%;
        align-items: flex-start;
      }
      .stepper-container {
        padding: 0 10px;
      }
    }
  </style>
</head>
<body>

  <div class="page-container">
    
    <!-- الهيدر والشعار (الشعار فوق الكلام) -->
    <div class="top-header">
      <div class="logo-graphic">
        <i class="fa-solid fa-hand-holding-heart"></i>
      </div>
      <div class="logo-text-group">
        <h1 class="logo-main-text">احسان</h1>
        <span class="logo-subtext">منصة ربط كبار السن بمقدمي الخدمة</span>
      </div>
    </div>

    <!-- شريط الخطوات -->
    <div class="stepper-container">
      <!-- خطوة المعلومات الشخصية (مكتملة ومظللة) -->
      <div class="step-item completed">
        <div class="step-circle">
          <i class="fa-solid fa-check"></i>
        </div>
        <span class="step-text">المعلومات الشخصية</span>
      </div>

      <!-- خطوة الوثائق المطلوبة (الحالية ومظللة) -->
      <div class="step-item active">
        <div class="step-circle">
          <i class="fa-regular fa-file-lines"></i>
        </div>
        <span class="step-text">الوثائق المطلوبة</span>
      </div>

      <!-- خطوة مراجعة وإرسال (غير مظللة) -->
      <div class="step-item">
        <div class="step-circle">
          <i class="fa-solid fa-arrow-up-from-bracket"></i>
        </div>
        <span class="step-text">مراجعة وارسال</span>
      </div>
    </div>

    <!-- نموذج الوثائق -->
    <form id="documentsForm" onsubmit="handleSubmit(event)">
      
      <!-- بطاقة صورة الهوية الشخصية -->
      <div class="upload-card">
        <div class="card-right-side">
          <div class="doc-illustration">
            <i class="fa-regular fa-id-card"></i>
          </div>
          <div class="doc-info">
            <h3>صورة الهوية الشخصية</h3>
            <p>يرجى رفع صورة واضحة للهويه الشخصية(للوجهين)</p>
          </div>
        </div>
        <div class="card-left-side">
          <button type="button" class="upload-btn" onclick="document.getElementById('idCardInput').click()">
            <i class="fa-solid fa-arrow-up-from-bracket"></i> رفع صورة
          </button>
          <span class="file-hint" id="idCardHint">JPG,PNG- الحد الاقصى</span>
          <input type="file" id="idCardInput" accept="image/png, image/jpeg" onchange="updateFileName(this, 'idCardHint')">
        </div>
      </div>

      <!-- بطاقة شهادة حسن سير وسلوك -->
      <div class="upload-card">
        <div class="card-right-side">
          <div class="doc-illustration">
            <i class="fa-solid fa-award"></i>
          </div>
          <div class="doc-info">
            <h3>شهادة حسن سير وسلوك</h3>
            <p>شهادة حديثة وصادرة من جهة حكومية معتمدة</p>
          </div>
        </div>
        <div class="card-left-side">
          <button type="button" class="upload-btn" onclick="document.getElementById('conductInput').click()">
            <i class="fa-solid fa-arrow-up-from-bracket"></i> رفع صورة
          </button>
          <span class="file-hint" id="conductHint">JPG,PNG- الحد الاقصى</span>
          <input type="file" id="conductInput" accept="image/png, image/jpeg" onchange="updateFileName(this, 'conductHint')">
        </div>
      </div>

      <!-- صندوق الأمان -->
      <div class="security-banner">
        <div class="security-content">
          <i class="fa-solid fa-lock"></i>
          <div class="security-text">
            <h4>خصوصية وامان</h4>
            <p>جميع الوثائق محميه ولن يتم مشاركتها مع اي طرف ثالث</p>
          </div>
        </div>
      </div>

      <!-- أزرار السابق والتالي -->
      <div class="navigation-row">
        <button type="button" class="nav-btn btn-prev" onclick="window.location.href={{ Js::from(route('frontend.volunteer.register')) }}">السابق</button>
        <button type="submit" class="nav-btn btn-next">التالي</button>
      </div>

    </form>

  </div>

  <script>
    // دالة لتحديث نص أو إظهار حالة الملف عند اختياره
    function updateFileName(input, hintId) {
      if (input.files && input.files[0]) {
        const fileName = input.files[0].name;
        const hintElement = document.getElementById(hintId);
        hintElement.textContent = "تم الرفع بنجاح";
        hintElement.style.color = "#31421e";
        hintElement.style.fontWeight = "700";
      }
    }

    // دالة عند الضغط على زر التالي لتوجهه إلى revinfo.html
    function handleSubmit(event) {
      event.preventDefault();
      window.location.href = @json(route('frontend.volunteer.review'));
    }
  </script>
</body>
</html>
