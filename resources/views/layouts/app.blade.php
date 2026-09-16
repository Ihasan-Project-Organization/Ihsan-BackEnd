<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>أنيس - بوابة كبير السن</title>

    {{-- الخطوط والأيقونات --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- استرجاع وتطبيق مقياس الخط المفضل فورياً --}}
    <script>
        (function() {
            try {
                var s = localStorage.getItem('ihsan_font_scale');
                if (s) {
                    document.documentElement.style.fontSize = s + '%';
                }
            } catch (e) {}
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg-color: #f4f0e8;
            --sidebar-bg: #2c4318;
            --sidebar-bg-end: #3d5a26;
            --primary-color: #7a9d50;
            --primary-hover: #4e6b35;
            --primary-light: #e6edd9;
            --text-main: #2d3748;
            --text-muted: #718096;
            --border-color: #ddd8cd;
            --card-bg: #ffffff;
            --support-card-bg: #e8e3d3;
            --sidebar-text: #ffffff;
            --shadow-sm: 0 1px 3px rgba(44,67,24,.06);
            --shadow-md: 0 4px 14px rgba(44,67,24,.08);
            --shadow-lg: 0 8px 28px rgba(44,67,24,.10);
            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 22px;
        }

        body {
            font-family: 'Alexandria', Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ══════════════ Sidebar ══════════════ */
        .sidebar {
            width: 260px;
            background: linear-gradient(175deg, var(--sidebar-bg) 0%, var(--sidebar-bg-end) 100%);
            border-left: 1px solid rgba(255,255,255,0.07);
            display: flex;
            flex-direction: column;
            padding: 20px 16px;
            position: fixed;
            height: 100vh;
            right: 0;
            top: 0;
            z-index: 100;
            transition: transform 0.35s cubic-bezier(.4,0,.2,1);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.15) transparent;
        }
        .sidebar::-webkit-scrollbar{width:5px}
        .sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.12);border-radius:10px}

        .logo-section {
            text-align: center;
            margin-bottom: 22px;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            font-size: 24px;
            font-weight: 900;
            color: var(--sidebar-text);
            margin-bottom: 4px;
        }

        .logo i {
            font-size: 25px;
            color: #a8c488;
        }

        .logo-subtitle {
            font-size: 10px;
            color: rgba(223,230,213,.8);
            margin-bottom: 8px;
            letter-spacing: .2px;
        }

        .role-pill {
            display: inline-block;
            background: rgba(255,255,255,.1);
            color: rgba(255,255,255,.85);
            font-size: 10px;
            font-weight: 700;
            padding: 3px 11px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,.12);
            letter-spacing: .15px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 3px;
            flex: 1;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 10px 13px;
            text-decoration: none;
            color: rgba(226,235,216,.85);
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 12px;
            transition: all 0.2s ease;
            position: relative;
        }

        .menu-item i {
            width: 22px;
            font-size: 14px;
            margin-left: 9px;
            text-align: center;
            color: rgba(168,196,136,.75);
            transition: color .2s;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.09);
            color: #ffffff;
        }
        .menu-item:hover i { color: rgba(168,196,136,1); }

        .menu-item.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, #6e8e44 100%);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 3px 12px rgba(122,157,80,.3);
        }

        .menu-item.active i {
            color: #ffffff;
        }

        .sidebar-cta-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px dashed rgba(255, 255, 255, 0.28);
            color: #ffffff;
            font-weight: 700;
        }

        .sidebar-cta-btn:hover {
            background: #ffffff;
            color: var(--sidebar-bg);
            border-color: #ffffff;
            border-style: solid;
        }

        .sidebar-cta-btn:hover i {
            color: var(--sidebar-bg);
        }

        .sidebar-badge {
            margin-right: auto;
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 10px;
        }

        .menu-item.active .sidebar-badge {
            background-color: rgba(255,255,255,.92);
            color: var(--sidebar-bg);
        }

        .support-card {
            background: linear-gradient(145deg, #ece7d9 0%, #e0dace 100%);
            border-radius: var(--radius-md);
            padding: 14px;
            text-align: center;
            margin-top: 16px;
            margin-bottom: 10px;
            border: 1px solid rgba(0,0,0,0.04);
        }

        .support-card p {
            font-size: 10px;
            font-weight: 700;
            color: #354e20;
            line-height: 1.6;
            margin-bottom: 9px;
        }

        .contact-btn {
            display: block;
            width: 100%;
            background: var(--sidebar-bg);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .contact-btn:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .logout-form {
            margin-top: 6px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(223,230,213,.75);
            padding: 8px 12px;
            border-radius: var(--radius-sm);
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
        }

        .logout-btn:hover {
            background-color: rgba(220, 38, 38, 0.15);
            border-color: rgba(220, 38, 38, 0.35);
            color: #fca5a5;
        }

        /* ══════════════ Main Content ══════════════ */
        .main-content {
            flex: 1;
            margin-right: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--bg-color);
            transition: margin-right 0.35s cubic-bezier(.4,0,.2,1);
        }

        .top-header {
            background: rgba(255,255,255,.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(221,216,205,.6);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: 0 1px 8px rgba(44,67,24,.04);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 10px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
        }

        .notification-icon-btn {
            position: relative;
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: rgba(248,250,246,.9);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #354e20;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .notification-icon-btn:hover {
            background: #eaf0e4;
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .notification-badge-dot {
            position: absolute;
            top: -4px;
            left: -4px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            font-size: 10px;
            font-weight: 900;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            box-shadow: 0 2px 6px rgba(220,38,38,.35);
        }

        .mobile-menu-btn {
            display: none;
            background: transparent;
            border: none;
            font-size: 20px;
            color: #354e20;
            cursor: pointer;
            padding: 6px;
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 95;
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
        }

        .content-body {
            flex: 1;
            padding: 22px 24px;
            max-width: 1300px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }

        /* ══════════════ Responsive ══════════════ */
        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(100%);
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .sidebar-backdrop.mobile-open {
                display: block;
            }
            .main-content {
                margin-right: 0;
            }
            .mobile-menu-btn {
                display: block;
            }
            .content-body {
                padding: 16px;
            }
        }

        @media(max-width:640px){
            .top-header{padding:9px 14px}
            .content-body{padding:12px}
            .header-actions{gap:6px}
        }

        /* ══════════════ Global polish ══════════════ */
        .content-body > .mb-6{margin-bottom:1rem}
        .content-body input,.content-body textarea,.content-body select{
            font-family:'Alexandria',Arial,sans-serif;
            transition: border-color .2s, box-shadow .2s;
        }

        :where(.content-body a,.content-body button,.sidebar a,.sidebar button):focus-visible{
            outline:3px solid rgba(122,157,80,.35);
            outline-offset:2px;
        }

        .content-body :where(input,textarea,select){border-color:#d8dfd0;border-radius:10px}
        .content-body :where(input,textarea,select):focus{
            border-color:#7a9d50;
            box-shadow:0 0 0 3px rgba(122,157,80,.12);
            outline:none;
        }
        .content-body :where(button,a){-webkit-tap-highlight-color:transparent}

        .content-body::-webkit-scrollbar,.content-body ::-webkit-scrollbar{width:6px;height:6px}
        .content-body::-webkit-scrollbar-thumb,.content-body ::-webkit-scrollbar-thumb{border-radius:20px;background:rgba(122,157,80,.25)}
        .content-body::-webkit-scrollbar-track,.content-body ::-webkit-scrollbar-track{background:transparent}

        /* ══════════════ Animations ══════════════ */
        @keyframes ihsan-fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
        @keyframes ihsan-pulse-soft{0%,100%{opacity:1}50%{opacity:.7}}

        .content-body > *{animation:ihsan-fadeIn .35s ease both}
        .content-body > :nth-child(2){animation-delay:.05s}
        .content-body > :nth-child(3){animation-delay:.1s}
        .content-body > :nth-child(4){animation-delay:.15s}

        @media(prefers-reduced-motion:reduce){
            *{scroll-behavior:auto!important;transition-duration:.01ms!important;animation-duration:.01ms!important;animation-delay:0s!important}
        }
    </style>
</head>
<body x-data="{ mobileSidebarOpen: false }">
    @php
        $user = Auth::user();
        $elderProfile = $user?->elderProfile;
        $activeCount = $user ? $user->serviceRequests()->active()->count() : 0;
        $needsActionCount = $user ? $user->serviceRequests()->needsAction()->count() : 0;
        $unreadNotificationsCount = $user ? $user->notifications()->where('is_read', false)->count() : 0;
    @endphp

    {{-- التعتيم للموبايل Backdrop --}}
    <div class="sidebar-backdrop" :class="{ 'mobile-open': mobileSidebarOpen }" @click="mobileSidebarOpen = false"></div>

    <div class="dashboard-container">
        {{-- الشريط الجانبي (Sidebar) مطابق لتصميم oldman_home المعتمد --}}
        <aside class="sidebar" :class="{ 'mobile-open': mobileSidebarOpen }">
            <div class="logo-section">
                <div class="logo">
                    <h2>أنيس</h2>
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <p class="logo-subtitle">منصة رعاية ومساندة كبار السن</p>
                <span class="role-pill">كبير السن / مستفيد</span>
            </div>

            <nav class="sidebar-menu">
                {{-- 1. الرئيسية --}}
                <a href="{{ route('dashboard') }}" 
                    class="menu-item {{ request()->routeIs('dashboard') && !request()->boolean('assistant') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    <span>الرئيسية</span>
                </a>

                {{-- صفحة المساعد الذكي المستقلة --}}
                <a href="{{ route('dashboard', ['assistant' => 1]) }}"
                    class="menu-item {{ request()->routeIs('dashboard') && request()->boolean('assistant') ? 'active' : '' }}">
                    <i class="fa-solid fa-robot"></i>
                    <span>المساعد الذكي</span>
                    <span class="sidebar-badge bg-white/20">AI</span>
                </a>

                {{-- 2. زر سريع: طلب مساعدة جديد --}}
                <button type="button" onclick="openCreateRequestModal()" 
                    class="menu-item sidebar-cta-btn text-right cursor-pointer w-full">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                    <span>طلب مساعدة جديد</span>
                    <span class="sidebar-badge bg-white/20">+</span>
                </button>

                {{-- 3. سجل طلباتي ومتابعة الخدمات --}}
                <a href="{{ route('service-requests.index') }}" 
                    class="menu-item {{ request()->routeIs('service-requests.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>طلباتي</span>
                    @if ($needsActionCount > 0)
                        <span class="sidebar-badge bg-amber-500 text-white animate-pulse" title="طلبات بحاجة لإجراء">{{ $needsActionCount }}</span>
                    @elseif ($activeCount > 0)
                        <span class="sidebar-badge">{{ $activeCount }}</span>
                    @endif
                </a>

                {{-- 4. الإشعارات --}}
                <a href="{{ route('notifications.index') }}" 
                    class="menu-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="fa-regular fa-bell"></i>
                    <span>الإشعارات</span>
                    @if ($unreadNotificationsCount > 0)
                        <span class="sidebar-badge">{{ $unreadNotificationsCount }}</span>
                    @endif
                </a>

                {{-- 5. الملف الشخصي والإعدادات --}}
                <a href="{{ route('profile.edit') }}" 
                    class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fa-regular fa-user"></i>
                    <span>الملف الشخصي</span>
                </a>
            </nav>

            {{-- كرت الدعم الفني المباشر لكبير السن --}}
            <div class="support-card">
                <p>نحن هنا لمساعدتك<br>فريق الدعم متاح دائماً</p>
                <a href="mailto:support@ihsan.app" class="contact-btn">تواصل معنا</a>
            </div>

            {{-- تسجيل الخروج الآمن --}}
            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>تسجيل خروج</span>
                </button>
            </form>
        </aside>

        {{-- المحتوى الرئيسي مع الهيدر العلوي --}}
        <main class="main-content">
            {{-- الهيدر العلوي (Top Header) --}}
            <header class="top-header">
                <div class="flex items-center gap-3">
                    <button type="button" class="mobile-menu-btn" @click="mobileSidebarOpen = !mobileSidebarOpen">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div>
                        <span class="text-xs font-bold text-slate-400">بوابة كبير السن والمستفيد</span>
                        <h2 class="text-sm font-black text-[#354e20]">
                            {{ match(true) {
                                request()->routeIs('dashboard') && request()->boolean('assistant') => 'المساعد الذكي',
                                request()->routeIs('dashboard') => 'الرئيسية ولوحة المتابعة',
                                request()->routeIs('service-requests.*') => 'سجل طلباتي ومتابعة الخدمات',
                                request()->routeIs('notifications.*') => 'مركز الإشعارات والتنبيهات',
                                request()->routeIs('profile.*') => 'الملف الشخصي والإعدادات',
                                default => 'منصة أنيس'
                            } }}
                        </h2>
                    </div>
                </div>

                <div class="header-actions">
                    {{-- زر إنشاء طلب جديد سريع في الهيدر --}}
                    @if (request()->routeIs('dashboard') && request()->boolean('assistant'))
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('assistant-start-request'))"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-[#354e20] text-white hover:bg-[#4e6b35] transition shadow-xs cursor-pointer">
                            <i class="fa-solid fa-plus text-[9px]"></i>
                            <span>ابدأ طلب خدمة</span>
                        </button>
                    @else
                        <button type="button" onclick="openCreateRequestModal()"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-[#354e20] text-white hover:bg-[#4e6b35] transition shadow-xs cursor-pointer">
                            <i class="fa-solid fa-plus text-[9px]"></i>
                            <span>طلب مساعدة جديد</span>
                        </button>
                    @endif

                    {{-- جرس الإشعارات --}}
                    <a href="{{ route('notifications.index') }}" class="notification-icon-btn" title="الإشعارات">
                        <i class="fa-regular fa-bell"></i>
                        @if ($unreadNotificationsCount > 0)
                            <span class="notification-badge-dot">{{ $unreadNotificationsCount }}</span>
                        @endif
                    </a>

                    {{-- الاسم والملف الشخصي --}}
                    <a href="{{ route('profile.edit') }}" class="hidden md:flex items-center gap-2 pr-2 border-r border-slate-200">
                        <div class="w-8 h-8 rounded-full bg-[#354e20] text-white flex items-center justify-center font-bold text-xs">
                            {{ mb_substr($user?->name ?? 'إ', 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-slate-800">{{ $user?->name }}</span>
                    </a>
                </div>
            </header>

            {{-- جسم الصفحة --}}
            <div class="content-body">
                @isset($header)
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endisset
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- المودالات الأساسية لكبير السن --}}
    @auth
        @include('service-requests.partials.create-modal')
        @include('service-requests.partials.action-modals')
    @endauth
</body>
</html>
