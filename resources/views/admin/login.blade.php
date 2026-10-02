<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="لوحة إدارة منصة أنيس - تسجيل دخول المديرين">
  <title>لوحة الإدارة | أنيس</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      background: #1a1f2e;
      background-image:
        radial-gradient(ellipse at 20% 50%, rgba(52, 78, 32, 0.15) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(52, 78, 32, 0.08) 0%, transparent 50%);
      font-family: 'Alexandria', 'Segoe UI', Tahoma, sans-serif;
      color: #e2e8f0;
    }

    .admin-login-card {
      width: min(92vw, 440px);
      background: rgba(30, 36, 50, 0.95);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 24px;
      padding: 40px 36px;
      box-shadow: 0 32px 80px rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(20px);
    }

    .admin-brand {
      text-align: center;
      margin-bottom: 32px;
    }

    .admin-brand .shield-icon {
      width: 64px;
      height: 64px;
      margin: 0 auto 16px;
      background: linear-gradient(135deg, #354e20, #4a6b2e);
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 28px;
      box-shadow: 0 8px 24px rgba(53, 78, 32, 0.3);
    }

    .admin-brand h1 {
      font-size: 22px;
      font-weight: 800;
      color: #ffffff;
      margin: 0 0 6px;
    }

    .admin-brand p {
      font-size: 13px;
      color: #94a3b8;
      font-weight: 500;
    }

    .input-group {
      margin-bottom: 20px;
    }

    .input-group label {
      display: block;
      font-weight: 700;
      margin-bottom: 8px;
      font-size: 13px;
      color: #cbd5e1;
    }

    .input-group input {
      width: 100%;
      padding: 14px 16px;
      border: 1.5px solid rgba(255, 255, 255, 0.1);
      border-radius: 14px;
      outline: none;
      font-size: 14px;
      font-family: inherit;
      background: rgba(255, 255, 255, 0.05);
      color: #e2e8f0;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }

    .input-group input::placeholder {
      color: #64748b;
    }

    .input-group input:focus {
      border-color: #4a6b2e;
      background: rgba(255, 255, 255, 0.08);
      box-shadow: 0 0 0 4px rgba(74, 107, 46, 0.15);
    }

    .options-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
      font-size: 13px;
    }

    .remember-label {
      display: flex;
      align-items: center;
      gap: 7px;
      font-weight: normal;
      cursor: pointer;
      color: #94a3b8;
    }

    .remember-label input[type="checkbox"] {
      accent-color: #4a6b2e;
    }

    .submit-btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #354e20, #4a6b2e);
      color: white;
      border: none;
      border-radius: 14px;
      font-size: 15px;
      font-weight: 800;
      font-family: inherit;
      cursor: pointer;
      transition: transform 0.15s, box-shadow 0.15s;
      box-shadow: 0 8px 24px rgba(53, 78, 32, 0.25);
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 32px rgba(53, 78, 32, 0.35);
    }

    .submit-btn:active {
      transform: scale(0.98);
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 20px;
      color: #64748b;
      font-size: 13px;
      text-decoration: none;
      transition: color 0.2s;
    }

    .back-link:hover {
      color: #94a3b8;
    }

    .alert-box {
      border-radius: 12px;
      padding: 12px 16px;
      margin-bottom: 18px;
      font-size: 13px;
      line-height: 1.6;
    }

    .alert-danger {
      background: rgba(185, 28, 28, 0.12);
      border: 1px solid rgba(239, 68, 68, 0.25);
      color: #fca5a5;
    }

    .alert-success {
      background: rgba(6, 95, 70, 0.12);
      border: 1px solid rgba(16, 185, 129, 0.25);
      color: #6ee7b7;
    }

    .security-note {
      margin-top: 24px;
      text-align: center;
      font-size: 11px;
      color: #475569;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    @media (max-width: 500px) {
      .admin-login-card {
        padding: 32px 22px;
        border-radius: 20px;
      }
      .admin-brand h1 { font-size: 20px; }
    }
  </style>
</head>
<body>

  <div class="admin-login-card">
    <div class="admin-brand">
      <div class="shield-icon">🛡️</div>
      <h1>لوحة إدارة أنيس</h1>
      <p>تسجيل دخول مديري النظام</p>
    </div>

    @if (session('status'))
      <div class="alert-box alert-success" role="status">
        {{ session('status') }}
      </div>
    @endif

    @if ($errors->has('email'))
      <div class="alert-box alert-danger" role="alert">
        {{ $errors->first('email') }}
      </div>
    @endif

    <form method="POST" action="{{ route('admin.login') }}">
      @csrf

      <div class="input-group">
        <label for="email">البريد الإلكتروني</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@anees.com" required autofocus autocomplete="username">
      </div>

      <div class="input-group">
        <label for="password">كلمة المرور</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password">
      </div>

      <div class="options-row">
        <label class="remember-label">
          <input type="checkbox" name="remember" @checked(old('remember'))> تذكرني
        </label>
      </div>

      <button type="submit" class="submit-btn">تسجيل الدخول</button>
    </form>

    <a href="{{ url('/') }}" class="back-link">← العودة إلى الموقع الرئيسي</a>

    <div class="security-note">
      <span>🔒</span>
      <span>هذه الصفحة مخصصة لمديري النظام فقط</span>
    </div>
  </div>

</body>
</html>
