<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إحسان - تفاصيل السكن للمسن</title>
  
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
      max-width: 1100px; /* تكبير الحد الأقصى للعرض */
      min-height: 85vh;  /* زيادة الارتفاع الأدنى */
      border-radius: 24px;
      padding: 60px 50px; /* زيادة الهوامش الداخلية */
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
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

    /* --- العنوان المباشر --- */
    .section-header {
      text-align: center;
      margin-bottom: 35px;
    }

    .section-header h2 {
      font-size: 28px;
      color: #111;
      font-weight: 800;
      margin-bottom: 10px;
    }

    .section-header p {
      font-size: 15px;
      color: #555;
    }

    /* --- حقول الإدخال --- */
    .form-group {
      margin-bottom: 24px;
    }

    .form-group label {
      display: block;
      font-size: 16px;
      font-weight: 700;
      color: #000;
      margin-bottom: 8px;
    }

    .input-wrapper {
      position: relative;
      display: flex;
      align-items: center;
    }

    .input-wrapper select,
    .input-wrapper input {
      width: 100%;
      padding: 16px 20px 16px 45px;
      border: 2px solid #000;
      border-radius: 10px;
      font-size: 15px;
      outline: none;
      background: #fff;
      appearance: none;
    }

    .input-wrapper i {
      position: absolute;
      left: 18px;
      font-size: 20px;
      color: #000;
      pointer-events: none;
    }

    /* --- كروت اختيار عنوان السكن --- */
    .housing-type-section {
      margin-top: 35px;
      margin-bottom: 40px;
    }

    .housing-type-section .section-label {
      font-size: 18px;
      font-weight: 800;
      color: #000;
      margin-bottom: 16px;
    }

    .housing-options {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
    }

    .housing-card {
      position: relative;
      border: 2px solid #a3b293;
      border-radius: 16px;
      padding: 25px 15px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 12px;
      cursor: pointer;
      background-color: #fff;
      transition: all 0.2s ease;
    }

    .housing-card input[type="radio"] {
      position: absolute;
      top: 12px;
      right: 12px;
      width: 20px;
      height: 20px;
      accent-color: #3b5228;
      cursor: pointer;
    }

    .housing-card i {
      font-size: 38px;
      color: #000;
    }

    .housing-card span {
      font-size: 15px;
      font-weight: 700;
      color: #000;
    }

    .housing-card:has(input:checked) {
      border-color: #3b5228;
      background-color: #f9fbf7;
    }

    /* --- أزرار التنقل --- */
    .action-buttons {
      display: flex;
      gap: 20px;
      margin-top: 20px;
    }

    .btn-next {
      flex: 1;
      padding: 16px;
      background-color: #3b5228;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 18px;
      font-weight: 700;
      cursor: pointer;
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
      text-decoration: none;
      text-align: center;
      display: inline-block;
    }
  </style>
</head>
<body>

  <div class="form-card">
    
    <!-- شريط الخطوات -->
    <div class="stepper">
      <div class="step">
        <div class="icon-box"><i class="fa-solid fa-user"></i></div>
        <span>المعلومات الشخصية</span>
      </div>
      <div class="line"></div>
      <div class="step active">
        <div class="icon-box"><i class="fa-solid fa-house"></i></div>
        <span>مكان السكن</span>
      </div>
      <div class="line"></div>
      <div class="step">
        <div class="icon-box"><i class="fa-solid fa-upload"></i></div>
        <span>مراجعة وإرسال</span>
      </div>
    </div>

    <!-- رؤوس الصفحة -->
    <div class="section-header">
      <h2>تفاصيل السكن للمسن</h2>
      <p>هذه المعلومات تساعدنا في مطابقة الطلب مع مقدمي الخدمة المناسبين</p>
    </div>

    <!-- النموذج -->
    <form id="housingForm" onsubmit="handleFormSubmit(event)">
      <!-- حقل المدينة -->
      <div class="form-group">
        <label>المدينة</label>
        <div class="input-wrapper">
          <select id="city" required>
            <option value="" disabled selected hidden>اختر المدينة</option>
            <option value="gaza">غزة</option>
            <option value="khanyounis">خان يونس</option>
            <option value="rafah">رفح</option>
            <option value="deir_al_balah">دير البلح</option>
            <option value="north_gaza">شمال غزة</option>
            <option value="middle_area">الوسطى</option>
          </select>
          <i class="fa-solid fa-globe"></i>
        </div>
      </div>

      <!-- حقل الحي / المنطقة -->
      <div class="form-group">
        <label>الحي / المنطقة</label>
        <div class="input-wrapper">
          <input type="text" id="district" placeholder="اختر الحي او المنطقة" required>
          <i class="fa-solid fa-building"></i>
        </div>
      </div>

      <!-- العنوان التفصيلي -->
      <div class="form-group">
        <label>العنوان التفصيلي</label>
        <div class="input-wrapper">
          <input type="text" id="addressDetails" placeholder="ادخل العنوان بالتفاصيل (اسم الشارع , رقم المبنى , اقرب معلم ...)" required>
          <i class="fa-solid fa-location-dot"></i>
        </div>
      </div>

      <!-- خيارات نوع السكن -->
      <div class="housing-type-section">
        <div class="section-label">عنوان السكن</div>
        <div class="housing-options">
          
          <label class="housing-card">
            <input type="radio" name="housing_type" value="apartment" checked>
            <i class="fa-solid fa-building"></i>
            <span>شقة</span>
          </label>

          <label class="housing-card">
            <input type="radio" name="housing_type" value="house">
            <i class="fa-solid fa-house-chimney"></i>
            <span>منزل مستقل</span>
          </label>

          <label class="housing-card">
            <input type="radio" name="housing_type" value="family">
            <i class="fa-solid fa-house-user"></i>
            <span>سكن مع عائلة</span>
          </label>

        </div>
      </div>

      <!-- الأزرار السفلية -->
      <div class="action-buttons">
        <button type="submit" class="btn-next">التالي</button>
        <button type="button" class="btn-prev" onclick="window.location.href={{ Js::from(route('frontend.elderly.register')) }}">السابق</button>
      </div>

    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const savedHousingData = JSON.parse(sessionStorage.getItem("housingStepData"));
      if (savedHousingData) {
        if (savedHousingData.city) document.getElementById("city").value = savedHousingData.city;
        if (savedHousingData.district) document.getElementById("district").value = savedHousingData.district;
        if (savedHousingData.addressDetails) document.getElementById("addressDetails").value = savedHousingData.addressDetails;
        
        if (savedHousingData.housingType) {
          const selectedRadio = document.querySelector(`input[name="housing_type"][value="${savedHousingData.housingType}"]`);
          if (selectedRadio) selectedRadio.checked = true;
        }
      }
    });

    function handleFormSubmit(event) {
      event.preventDefault();

      const housingData = {
        city: document.getElementById("city").value,
        district: document.getElementById("district").value,
        addressDetails: document.getElementById("addressDetails").value,
        housingType: document.querySelector('input[name="housing_type"]:checked')?.value || ''
      };

      sessionStorage.setItem("housingStepData", JSON.stringify(housingData));
      window.location.href = @json(route('frontend.elderly.review'));
    }
  </script>

</body>
</html>
