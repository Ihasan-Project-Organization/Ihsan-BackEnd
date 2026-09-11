<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'إحسان') }} - بوابة مقدم الخدمة</title>

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

    {{-- Fonts & Icons --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg-color: #f2ede4;
            --sidebar-bg: #354e20;
            --primary-color: #83a55b;
            --primary-hover: #4e6b35;
            --primary-light: #e6edd9;
            --text-main: #2d3748;
            --text-muted: #718096;
            --border-color: #e2dcd0;
            --card-bg: #ffffff;
            --support-card-bg: #e8e3d3;
            --sidebar-text: #ffffff;
        }

        body {
            font-family: 'Alexandria', Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background-color: var(--sidebar-bg);
            border-left: 1px solid rgba(255,255,255,0.1);
            display: flex;
            flex-direction: column;
            padding: 24px 20px;
            position: fixed;
            height: 100vh;
            right: 0;
            top: 0;
            z-index: 100;
            transition: transform 0.3s ease;
            overflow-y: auto;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 26px;
            font-weight: 900;
            color: var(--sidebar-text);
            margin-bottom: 4px;
        }

        .logo i {
            font-size: 28px;
            color: #b8cfa0;
        }

        .logo-subtitle {
            font-size: 11px;
            color: #dfe6d5;
            margin-bottom: 8px;
        }

        .role-pill {
            display: inline-block;
            background-color: rgba(255,255,255,0.15);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex: 1;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 11px 16px;
            text-decoration: none;
            color: #e2ebd8;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .menu-item i {
            width: 24px;
            font-size: 16px;
            margin-left: 10px;
            text-align: center;
            color: #b8cfa0;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        .menu-item.active {
            background-color: var(--primary-color);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .menu-item.active i {
            color: #ffffff;
        }

        .sidebar-badge {
            margin-right: auto;
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .menu-item.active .sidebar-badge {
            background-color: #ffffff;
            color: var(--sidebar-bg);
        }

        .support-card {
            background-color: var(--support-card-bg);
            border-radius: 18px;
            padding: 16px;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 12px;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .support-card p {
            font-size: 11px;
            font-weight: 700;
            color: #354e20;
            line-height: 1.5;
            margin-bottom: 10px;
        }

        .contact-btn {
            display: block;
            width: 100%;
            background-color: var(--sidebar-bg);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .contact-btn:hover {
            background-color: var(--primary-hover);
        }

        .logout-form {
            margin-top: 4px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #dfe6d5;
            padding: 9px 14px;
            border-radius: 14px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background-color: rgba(220, 38, 38, 0.2);
            border-color: rgba(220, 38, 38, 0.4);
            color: #fca5a5;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-right: 280px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--bg-color);
            transition: margin-right 0.3s ease;
        }

        .top-header {
            background-color: #ffffff;
            border-bottom: 1px solid var(--border-color);
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: 0 1px 4px rgba(0,0,0,0.03);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f8faf6;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 700;
            color: #354e20;
            text-decoration: none;
            transition: all 0.2s;
        }

        .header-btn:hover {
            background: #eef2e8;
        }

        .notification-icon-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: #f8faf6;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #354e20;
            font-size: 16px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .notification-icon-btn:hover {
            background: #eef2e8;
        }

        .notification-badge-dot {
            position: absolute;
            top: -3px;
            left: -3px;
            background-color: #e02424;
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
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
            background: rgba(0,0,0,0.5);
            z-index: 95;
            backdrop-filter: blur(2px);
        }

        .content-body {
            flex: 1;
            padding: 28px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }

        /* Responsive */
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
    </style>
</head>
<body x-data="{ mobileSidebarOpen: false }">
    @php
        $user = Auth::user();
        $providerProfile = $user?->serviceProviderProfile;
        $tier = (int) ($providerProfile?->tier ?? 1);
        $isAvailable = (bool) ($providerProfile?->is_available ?? false);
        $availableCount = \App\Models\ServiceRequest::availableForProvider($user)->count();
        $activeTasksCount = $providerProfile ? \App\Models\ServiceRequest::where('provider_id', $providerProfile->id)
            ->whereIn('status', [
                \App\Models\ServiceRequest::STATUS_ACCEPTED,
                \App\Models\ServiceRequest::STATUS_ASSIGNED,
                \App\Models\ServiceRequest::STATUS_IN_PROGRESS,
                \App\Models\ServiceRequest::STATUS_PENDING_CONFIRMATION,
                \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED
            ])->count() : 0;
        $unreadNotificationsCount = $user ? $user->notifications()->where('is_read', false)->count() : 0;
    @endphp

    {{-- التعتيم للموبايل Backdrop --}}
    <div class="sidebar-backdrop" :class="{ 'mobile-open': mobileSidebarOpen }" @click="mobileSidebarOpen = false"></div>

    <div class="dashboard-container">
        {{-- الشريط الجانبي (Sidebar) مطابق لتصميم oldman_home ومعدل لأقسام مقدم الخدمة --}}
        <aside class="sidebar" :class="{ 'mobile-open': mobileSidebarOpen }">
            <div class="logo-section">
                <div class="logo">
                    <h2>إحسان</h2>
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </div>
                <p class="logo-subtitle">منصة ربط كبار السن بمقدمي الخدمة</p>
                <span class="role-pill">مقدم خدمة متطوع</span>
            </div>

            <nav class="sidebar-menu">
                <a href="{{ route('provider.dashboard') }}" 
                    class="menu-item {{ request()->routeIs('provider.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i>
                    <span>الرئيسية</span>
                </a>

                <a href="{{ route('provider.available') }}" 
                    class="menu-item {{ request()->routeIs('provider.available') ? 'active' : '' }}">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                    <span>الطلبات المتاحة</span>
                    @if ($availableCount > 0)
                        <span class="sidebar-badge">{{ $availableCount }}</span>
                    @endif
                </a>

                <a href="{{ route('provider.tasks') }}" 
                    class="menu-item {{ request()->routeIs('provider.tasks*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>مهامي</span>
                    @if ($activeTasksCount > 0)
                        <span class="sidebar-badge">{{ $activeTasksCount }}</span>
                    @endif
                </a>

                <a href="{{ route('provider.performance') }}" 
                    class="menu-item {{ request()->routeIs('provider.performance') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>الأداء والتقييم</span>
                </a>

                <a href="{{ route('provider.certificates') }}" 
                    class="menu-item {{ request()->routeIs('provider.certificates*') ? 'active' : '' }}">
                    <i class="fa-solid fa-award"></i>
                    <span>شهادات التطوع</span>
                </a>

                <a href="{{ route('provider.availability') }}" 
                    class="menu-item {{ request()->routeIs('provider.availability') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock"></i>
                    <span>التوفر والإعدادات</span>
                </a>

                <a href="{{ route('notifications.index') }}" 
                    class="menu-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <i class="fa-regular fa-bell"></i>
                    <span>الإشعارات</span>
                    @if ($unreadNotificationsCount > 0)
                        <span class="sidebar-badge">{{ $unreadNotificationsCount }}</span>
                    @endif
                </a>

                <a href="{{ route('profile.edit') }}" 
                    class="menu-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    <i class="fa-regular fa-user"></i>
                    <span>الملف الشخصي</span>
                </a>
            </nav>

            <div class="support-card">
                <p>نحن هنا لمساعدتك<br>فريق الدعم متاح دائماً</p>
                <a href="mailto:support@ihsan.app" class="contact-btn">تواصل معنا</a>
            </div>

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
                        <span class="text-xs font-bold text-slate-400">بوابة مقدم الخدمة</span>
                        <h2 class="text-sm font-black text-[#354e20]">
                            {{ match(true) {
                                request()->routeIs('provider.dashboard') => 'لوحة التحكم والمتابعة',
                                request()->routeIs('provider.available') => 'فرص المساعدة والطلبات المتاحة',
                                request()->routeIs('provider.tasks*') => 'سجل ومتابعة المهام',
                                request()->routeIs('provider.performance') => 'مستوى الأداء والتقييم',
                                request()->routeIs('provider.certificates*') => 'شهادات وساعات التطوع الرقمية',
                                request()->routeIs('provider.availability') => 'إعدادات التوفر والخدمة',
                                request()->routeIs('notifications.*') => 'مركز الإشعارات والتنبيهات',
                                request()->routeIs('profile.*') => 'الملف الشخصي والإعدادات',
                                default => 'منصة إحسان'
                            } }}
                        </h2>
                    </div>
                </div>

                <div class="header-actions">
                    {{-- شارة المستوى Tier --}}
                    <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black border {{ $tier === 3 ? 'bg-amber-100 text-amber-900 border-amber-300' : ($tier === 2 ? 'bg-emerald-100 text-emerald-900 border-emerald-300' : 'bg-slate-100 text-slate-800 border-slate-300') }}">
                        <span>🏆</span>
                        <span>المستوى {{ $tier }}</span>
                    </span>

                    {{-- زر التوفر السريع --}}
                    <form method="POST" action="{{ route('provider.availability.update') }}" class="hidden sm:inline">
                        @csrf
                        <input type="hidden" name="is_available" value="{{ $isAvailable ? '0' : '1' }}">
                        <button type="submit" class="header-btn" title="اضغط لتغيير حالة التوفر سريعاً">
                            <span class="h-2 w-2 rounded-full {{ $isAvailable ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                            <span>{{ $isAvailable ? 'متاح' : 'غير متاح' }}</span>
                        </button>
                    </form>

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
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                        <span class="text-xs font-bold text-slate-800">{{ $user->name }}</span>
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

    {{-- مودالات مقدم الخدمة لتأكيد الإنجاز، التأخير، الاعتذار، وتقييم كبير السن --}}
    @include('provider.partials.modals')
</body>
</html>
