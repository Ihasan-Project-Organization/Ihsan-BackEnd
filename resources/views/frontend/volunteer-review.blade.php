<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إحسان - مراجعة معلوماتك ورفع الوثائق</title>
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

    .form-card {
      background: #ffffff;
      width: 100%;
      max-width: 1100px;
      border-radius: 24px;
      padding: 45px 55px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
    }

    /* --- الهيدر العلوي وشعار إحسان --- */
    .brand-header {
      text-align: center;
      margin-bottom: 30px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .logo-graphic {
      font-size: 46px;
      color: #8c967e;
      margin-bottom: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .brand-title {
      font-size: 40px;
      font-weight: 800;
      color: #31421e;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      line-height: 1.1;
    }

    .brand-subtitle {
      font-size: 15px;
      color: #555;
      font-weight: 500;
      margin-top: 4px;
    }

    /* --- شريط الخطوات (Stepper) --- */
    .stepper {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 40px;
      position: relative;
      max-width: 850px;
      margin-left: auto;
      margin-right: auto;
      padding: 0 35px;
    }

    .stepper::before {
      content: '';
      position: absolute;
      top: 30px;
      left: 15%;
      right: 15%;
      height: 2px;
      border-top: 2.5px dashed #31421e;
      z-index: 1;
    }

    .step {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
      font-size: 15px;
      font-weight: 700;
      color: #31421e;
      position: relative;
      z-index: 2;
    }

    .icon-box {
      width: 65px;
      height: 65px;
      border-radius: 50%;
      border: 2px solid #31421e;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      background: #fff;
      color: #31421e;
    }

    .step.completed .icon-box,
    .step.active .icon-box {
      background-color: #31421e;
      color: #fff;
    }

    /* --- ترويسة الصفحة --- */
    .review-header {
      text-align: center;
      margin-bottom: 35px;
    }

    .review-header i {
      font-size: 48px;
      color: #31421e;
      margin-bottom: 10px;
    }

    .review-header h2 {
      font-size: 26px;
      font-weight: 800;
      color: #000;
      margin-bottom: 6px;
    }

    .review-header p {
      font-size: 15px;
      color: #555;
    }

    /* --- كروت عرض البيانات --- */
    .review-card {
      border: 2px solid #dcd6cd;
      border-radius: 20px;
      padding: 28px 35px;
      margin-bottom: 25px;
      background-color: #fff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.02);
    }

    .review-card-title {
      font-size: 20px;
      font-weight: 800;
      color: #1a1a1a;
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
      font-weight: 800;
      color: #000;
    }

    .info-value {
      font-size: 16px;
      font-weight: 700;
      color: #222;
      text-align: left;
    }

    /* --- كروت الوثائق المرفوعة --- */
    .docs-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    .doc-box {
      border: 2px solid #dcd6cd;
      border-radius: 16px;
      padding: 24px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 12px;
      position: relative;
      background-color: #fff;
      cursor: pointer;
      transition: border-color 0.2s;
    }

    .doc-box:hover {
      border-color: #31421e;
    }

    /* إخفاء حقل الملف الفعلي وجعله يغطي الكارت لسهولة الضغط عليه */
    .doc-box input[type="file"] {
      position: absolute;
      width: 100%;
      height: 100%;
      top: 0;
      left: 0;
      opacity: 0;
      cursor: pointer;
    }

    /* دائرة الصح الخضراء */
    .doc-box .check-icon {
      position: absolute;
      top: 14px;
      left: 14px;
      background-color: #28a745; /* لون أخضر واضح */
      color: #fff;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      display: none; /* مخفية حتى يتم الرفع */
      align-items: center;
      justify-content: center;
      font-size: 16px;
      box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
    }

    .doc-box-title {
      font-size: 17px;
      font-weight: 800;
      color: #000;
    }

    .doc-file-name {
      font-size: 15px;
      color: #666;
      font-weight: bold;
      word-break: break-all;
      text-align: center;
    }

    /* --- شريط الموافقة والشروط --- */
    .terms-banner {
      background-color: #e3e7d5;
      border-radius: 18px;
      padding: 20px 25px;
      text-align: center;
      color: #31421e;
      font-size: 15px;
      font-weight: bold;
      margin: 30px 0 35px;
    }

    /* --- أزرار التحكم --- */
    .action-buttons {
      display: flex;
      gap: 25px;
    }

    .btn-submit {
      flex: 1;
      padding: 18px;
      background-color: #31421e;
      color: #fff;
      border: none;
      border-radius: 16px;
      font-size: 18px;
      font-weight: bold;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      font-family: 'Alexandria', sans-serif;
      transition: background-color 0.2s;
    }

    .btn-submit:hover {
      background-color: #243215;
    }

    .btn-prev {
      flex: 1;
      padding: 18px;
      background-color: #fff;
      color: #31421e;
      border: 2px solid #31421e;
      border-radius: 16px;
      font-size: 18px;
      font-weight: bold;
      cursor: pointer;
      font-family: 'Alexandria', sans-serif;
      text-align: center;
      transition: background-color 0.2s;
    }

    .btn-prev:hover {
      background-color: #f7f9f4;
    }

    @media (max-width: 768px) {
      .docs-grid {
        grid-template-columns: 1fr;
      }
      .stepper {
        padding: 0 10px;
      }
      .form-card {
        padding: 25px 20px;
      }
    }
  </style>
</head>
<body>

  <div class="form-card">

    <!-- الشعار -->
    <div class="brand-header">
      <div class="logo-graphic">
        <i class="fa-solid fa-hand-holding-heart"></i>
      </div>
      <div class="brand-title">
        احسان
      </div>
      <div class="brand-subtitle">منصة ربط كبار السن بمقدمي الخدمة</div>
    </div>
    
    <!-- شريط الخطوات -->
    <div class="stepper">
      <div class="step completed">
        <div class="icon-box"><i class="fa-solid fa-check"></i></div>
        <span>المعلومات الشخصيه</span>
      </div>
      <div class="step completed">
        <div class="icon-box"><i class="fa-solid fa-check"></i></div>
        <span>الوثائق المطلوبة</span>
      </div>
      <div class="step active">
        <div class="icon-box"><i class="fa-solid fa-arrow-up-from-bracket"></i></div>
        <span>مراجعة وارسال</span>
      </div>
    </div>

    <!-- ترويسة الصفحة -->
    <div class="review-header">
      <i class="fa-regular fa-file-lines"></i>
      <h2>مراجعة معلوماتك</h2>
      <p>يرجى رفع المستندات المطلوبة ومراجعة التفاصيل قبل إرسال الطلب</p>
    </div>

    <!-- كارت المعلومات الشخصية -->
    <div class="review-card">
      <div class="review-card-title">المعلومات الشخصيه</div>
      <div class="info-table">
        <div class="info-row">
          <span class="info-label">الاسم الكامل</span>
          <span class="info-value" id="val-fullName">--</span>
        </div>
        <div class="info-row">
          <span class="info-label">تاريخ الميلاد</span>
          <span class="info-value" id="val-dob">--</span>
        </div>
        <div class="info-row">
          <span class="info-label">رقم الجوال</span>
          <span class="info-value" id="val-phone">--</span>
        </div>
        <div class="info-row">
          <span class="info-label">البريد الالكتروني</span>
          <span class="info-value" id="val-email">--</span>
        </div>
        <div class="info-row">
          <span class="info-label">رقم الهوية</span>
          <span class="info-value" id="val-idNumber">--</span>
        </div>
        <div class="info-row">
          <span class="info-label">الجنس</span>
          <span class="info-value" id="val-gender">--</span>
        </div>
      </div>
    </div>

    <!-- كارت الوثائق المرفوعة (تمت إضافة حقول الرفع الفعلي هنا) -->
    <div class="review-card">
      <div class="review-card-title">الوثائق المرفوعة</div>
      <div class="docs-grid">
        
        <!-- صندوق رفع الهوية الشخصية -->
        <div class="doc-box" id="box-idDoc">
          <input type="file" id="file-idDoc" accept="image/*,.pdf" onchange="handleFileUpload('idDoc')">
          <div class="check-icon" id="icon-idDoc"><i class="fa-solid fa-check"></i></div>
          <span class="doc-box-title">صورة الهوية الشخصية</span>
          <span class="doc-file-name" id="val-idDoc">انقر لرفع المستند</span>
        </div>

        <!-- صندوق رفع شهادة حسن السير والسلوك -->
        <div class="doc-box" id="box-conductDoc">
          <input type="file" id="file-conductDoc" accept="image/*,.pdf" onchange="handleFileUpload('conductDoc')">
          <div class="check-icon" id="icon-conductDoc"><i class="fa-solid fa-check"></i></div>
          <span class="doc-box-title">شهادة حسن سير وسلوك</span>
          <span class="doc-file-name" id="val-conductDoc">انقر لرفع المستند</span>
        </div>

      </div>
    </div>

    <!-- ملاحظة الشروط -->
    <div class="terms-banner">
      بارسال طلبك فانك توافق على الشروط والاحكام وسياسة الخصوصيه الخاصة بمنصة الاحسان
    </div>

    <!-- الأزرار -->
    <div class="action-buttons">
      <button type="button" class="btn-prev" onclick="window.location.href={{ Js::from(route('frontend.volunteer.documents')) }}">السابق</button>
      <button type="button" class="btn-submit" onclick="submitRequest()">
        <i class="fa-regular fa-paper-plane"></i> ارسال الطلب
      </button>
    </div>

  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      // جلب البيانات الشخصية من localStorage
      if (localStorage.getItem("fullName")) document.getElementById("val-fullName").innerText = localStorage.getItem("fullName");
      if (localStorage.getItem("dob")) document.getElementById("val-dob").innerText = localStorage.getItem("dob");
      if (localStorage.getItem("phone")) document.getElementById("val-phone").innerText = localStorage.getItem("phone");
      if (localStorage.getItem("email")) document.getElementById("val-email").innerText = localStorage.getItem("email");
      if (localStorage.getItem("idNumber")) document.getElementById("val-idNumber").innerText = localStorage.getItem("idNumber");
      if (localStorage.getItem("gender")) document.getElementById("val-gender").innerText = localStorage.getItem("gender");

      // فحص وتحميل حالة الهوية إذا كانت محفوظة مسبقاً
      const savedIdDoc = localStorage.getItem("idDocName");
      if (savedIdDoc) {
        document.getElementById("val-idDoc").innerText = savedIdDoc;
        document.getElementById("icon-idDoc").style.display = "flex";
        document.getElementById("box-idDoc").style.borderColor = "#28a745";
      }

      // فحص وتحميل حالة شهادة السير والسلوك إذا كانت محفوظة مسبقاً
      const savedConductDoc = localStorage.getItem("conductDocName");
      if (savedConductDoc) {
        document.getElementById("val-conductDoc").innerText = savedConductDoc;
        document.getElementById("icon-conductDoc").style.display = "flex";
        document.getElementById("box-conductDoc").style.borderColor = "#28a745";
      }
    });

    // دالة التعامل مع رفع الملف وإظهار علامة الصح تلقائياً
    function handleFileUpload(docType) {
      const fileInput = document.getElementById(`file-${docType}`);
      const fileNameSpan = document.getElementById(`val-${docType}`);
      const checkIcon = document.getElementById(`icon-${docType}`);
      const docBox = document.getElementById(`box-${docType}`);

      if (fileInput.files && fileInput.files.length > 0) {
        const fileName = fileInput.files[0].name;
        
        // تحديث النص ليعرض اسم الملف المرفوع
        fileNameSpan.innerText = fileName;
        fileNameSpan.style.color = "#28a745";

        // إظهار دائرة الصح الخضراء فوراً
        checkIcon.style.display = "flex";
        docBox.style.borderColor = "#28a745";

        // حفظ اسم الملف في localStorage لضمان استمراره
        localStorage.setItem(`${docType}Name`, fileName);
      }
    }

    function submitRequest() {
      const idDoc = localStorage.getItem("idDocName");
      const conductDoc = localStorage.getItem("conductDocName");

      if (!idDoc || !conductDoc) {
        alert("يرجى التأكد من رفع جميع الوثائق المطلوبة قبل إرسال الطلب.");
        return;
      }

      alert("تم إرسال الطلب بنجاح!");
    }
  </script>

</body>
</html>
