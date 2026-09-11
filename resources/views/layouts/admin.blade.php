<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'إحسان') }} - لوحة الإدارة</title>

    <script>
        (function() {
            try {
                var s = localStorage.getItem('ihsan_font_scale');
                if (s) { document.documentElement.style.fontSize = s + '%'; }
            } catch (e) {}
        })();
    </script>

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
            --accent-gold: #f4a400;
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

        .sidebar {
            width: 270px;
            background-color: var(--sidebar-bg);
            border-left: 1px solid rgba(255,255,255,0.1);
            display: flex;
            flex-direction: column;
            padding: 22px 18px;
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
            margin-bottom: 24px;
            padding-bottom: 18px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 24px;
            font-weight: 900;
            color: var(--sidebar-text);
            margin-bottom: 4px;
        }

        .logo i { font-size: 24px; color: #b8cfa0; }

        .logo-subtitle { font-size: 11px; color: #dfe6d5; margin-bottom: 10px; }

        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.22);
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .role-pill.super {
            background: linear-gradient(135deg, #f4a400, #e08800);
            color: #354e20;
            border: none;
            font-weight: 800;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1;
        }

        .menu-section-label {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.06em;
            color: #a4bc8c;
            padding: 12px 12px 4px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            text-decoration: none;
            color: #e2ebd8;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
            gap: 10px;
        }

        .menu-item i {
            width: 22px;
            font-size: 15px;
            text-align: center;
            color: #b8cfa0;
            flex-shrink: 0;
        }

        .menu-item:hover {
            background-color: rgba(255,255,255,0.12);
            color: #ffffff;
        }

        .menu-item.active {
            background-color: var(--primary-color);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .menu-item.active i { color: #ffffff; }

        .sidebar-badge {
            margin-right: auto;
            background-color: rgba(255,255,255,0.2);
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

        .admin-info-card {
            background-color: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.14);
            border-radius: 16px;
            padding: 12px 14px;
            margin-top: 18px;
            margin-bottom: 10px;
        }

        .admin-info-card .admin-name { font-size: 12px; font-weight: 800; color: #fff; }

        .admin-info-card .admin-email {
            font-size: 10.5px;
            color: #dfe6d5;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .logout-form { margin-top: 4px; }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.2);
            color: #e2ebd8;
            padding: 8px 14px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Alexandria', sans-serif;
        }

        .logout-btn:hover {
            background-color: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.4);
            color: #fca5a5;
        }

        .main-content {
            flex: 1;
            margin-right: 270px;
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

        .header-breadcrumb { display: flex; flex-direction: column; gap: 1px; }

        .header-breadcrumb .label {
            font-size: 10px;
            font-weight: 700;
            color: #9ca3af;
            letter-spacing: 0.04em;
        }

        .header-breadcrumb .title {
            font-size: 15px;
            font-weight: 900;
            color: #354e20;
        }

        .header-actions { display: flex; align-items: center; gap: 12px; }

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
            padding: 24px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
        }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .sidebar-backdrop.mobile-open { display: block; }
            .main-content { margin-right: 0; }
            .mobile-menu-btn { display: block; }
            .content-body { padding: 16px; }
        }
    </style>
</head>
<body x-data="{ mobileSidebarOpen: false }">
    @php
        $adminUser        = Auth::user();
        $adminRecord      = $adminUser?->admin;
        $isSuperAdmin     = $adminRecord?->admin_level === 'super_admin';
        $pendingUsersCount = \App\Models\User::where('status', 'pending')->count();
    @endphp

    <div class="sidebar-backdrop" :class="{ 'mobile-open': mobileSidebarOpen }" @click="mobileSidebarOpen = false"></div>

    <div class="dashboard-container">
        <aside class="sidebar" :class="{ 'mobile-open': mobileSidebarOpen }">
            <div class="logo-section">
                <div class="logo">
                    <h2>إحسان</h2>
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <p class="logo-subtitle">منصة ربط كبار السن بمقدمي الخدمة</p>
                <span class="role-pill {{ $isSuperAdmin ? 'super' : '' }}">
                    <i class="fa-solid {{ $isSuperAdmin ? 'fa-crown' : 'fa-user-shield' }}"></i>
                    {{ $isSuperAdmin ? 'مدير النظام الأعلى' : 'مدير النظام' }}
                </span>
            </div>

            <nav class="sidebar-menu">
                <span class="menu-section-label">لوحة الإدارة</span>

                {{-- 1. لوحة التحكم --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-pie"></i>
                    <span>لوحة التحكم</span>
                </a>

                {{-- 2. مراجعة الاعتمادات --}}
                <a href="{{ route('admin.approvals.index') }}"
                    class="menu-item {{ request()->routeIs('admin.approvals*') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-check"></i>
                    <span>مراجعة الاعتمادات</span>
                    @if ($pendingUsersCount > 0)
                        <span class="sidebar-badge">{{ $pendingUsersCount }}</span>
                    @endif
                </a>

                {{-- 3. إدارة الطلبات --}}
                <a href="{{ Route::has('admin.requests.index') ? route('admin.requests.index') : '#' }}"
                    class="menu-item {{ request()->routeIs('admin.requests*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clipboard-list"></i>
                    <span>إدارة الطلبات</span>
                </a>

                {{-- 4. الشكاوى وتنبيهات الموثوقية --}}
                <a href="{{ Route::has('admin.complaints.index') ? route('admin.complaints.index') : (Route::has('admin.reports.index') ? route('admin.reports.index') : '#') }}"
                    class="menu-item {{ (request()->routeIs('admin.complaints*') || request()->routeIs('admin.reports*')) ? 'active' : '' }}">
                    <i class="fa-solid fa-shield-halved"></i>
                    <span>الشكاوى والموثوقية</span>
                </a>

                {{-- 5. إدارة المستخدمين --}}
                <a href="{{ Route::has('admin.users.index') ? route('admin.users.index') : '#' }}"
                    class="menu-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>إدارة المستخدمين</span>
                </a>

                @if ($isSuperAdmin)
                    <span class="menu-section-label">إدارة النظام (Super Admin)</span>

                    {{-- 6. إدارة المديرين --}}
                    <a href="{{ Route::has('admin.admins.index') ? route('admin.admins.index') : '#' }}"
                        class="menu-item {{ request()->routeIs('admin.admins*') ? 'active' : '' }}">
                        <i class="fa-solid fa-crown"></i>
                        <span>إدارة المديرين</span>
                    </a>

                    {{-- 7. إعدادات النظام --}}
                    <a href="{{ Route::has('admin.settings.index') ? route('admin.settings.index') : '#' }}"
                        class="menu-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                        <i class="fa-solid fa-sliders"></i>
                        <span>إعدادات النظام</span>
                    </a>
                @endif

                {{-- 8. سجل النظام الإداري (Audit Log) --}}
                <span class="menu-section-label">السجلات والتدقيق</span>
                <a href="{{ Route::has('admin.audit-log.index') ? route('admin.audit-log.index') : '#' }}"
                    class="menu-item {{ request()->routeIs('admin.audit-log*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>سجل النظام (Audit Log)</span>
                </a>
            </nav>

            <div class="admin-info-card">
                <div class="admin-name">{{ $adminUser->name }}</div>
                <div class="admin-email">{{ $adminUser->email }}</div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>تسجيل خروج</span>
                </button>
            </form>
        </aside>

        <main class="main-content">
            <header class="top-header">
                <div class="flex items-center gap-3">
                    <button type="button" class="mobile-menu-btn" @click="mobileSidebarOpen = !mobileSidebarOpen">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="header-breadcrumb">
                        <span class="label">لوحة الإدارة — إحسان</span>
                        <h2 class="title">
                            {{ match(true) {
                                request()->routeIs('admin.dashboard')       => 'نظرة عامة على المنصة',
                                request()->routeIs('admin.approvals*')      => 'مراجعة طلبات الاعتماد',
                                request()->routeIs('admin.requests*')       => 'إدارة الطلبات',
                                request()->routeIs('admin.complaints*')     => 'الشكاوى وتنبيهات الموثوقية',
                                request()->routeIs('admin.reports*')        => 'الشكاوى وتنبيهات الموثوقية',
                                request()->routeIs('admin.users*')          => 'إدارة المستخدمين',
                                request()->routeIs('admin.admins*')         => 'إدارة المديرين',
                                request()->routeIs('admin.settings*')       => 'إعدادات النظام العامة',
                                request()->routeIs('admin.audit-log*')      => 'سجل النظام والتدقيق',
                                default                                     => 'لوحة الإدارة',
                            } }}
                        </h2>
                    </div>
                </div>

                <div class="header-actions">
                    <div class="hidden md:flex items-center gap-2 pr-2 border-r border-gray-200">
                        <div class="w-8 h-8 rounded-full bg-[#354e20] text-white flex items-center justify-center font-bold text-xs">
                            {{ mb_substr($adminUser->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="text-xs font-bold text-gray-800">{{ $adminUser->name }}</div>
                            <div class="text-[10px] text-gray-500">{{ $isSuperAdmin ? 'مدير النظام الأعلى' : 'مدير النظام' }}</div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="content-body">
                @if (session('success'))
                    <div style="background:#d1fae5; border:1px solid #6ee7b7; color:#065f46; border-radius:12px; padding:12px 18px; margin-bottom:20px; font-size:13px; font-weight:700; display:flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; border-radius:12px; padding:12px 18px; margin-bottom:20px; font-size:13px; font-weight:700; display:flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
