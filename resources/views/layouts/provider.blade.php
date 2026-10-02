<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'أنيس') }} - بوابة مقدم الخدمة</title>

    <script>
        (function() {
            try {
                var s = localStorage.getItem('anees_font_scale');
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
            --bg-color: #f7f5f0;
            --sidebar-bg: linear-gradient(175deg, #2a3f1a 0%, #3b5728 40%, #2d4420 100%);
            --primary-color: #6b9b3a;
            --primary-hover: #4e7a28;
            --primary-light: #e8f0de;
            --accent-gold: #c9a84c;
            --accent-warm: #e8a849;
            --text-main: #1e2a16;
            --text-muted: #6b7c60;
            --border-color: #e0ddd4;
            --card-bg: #ffffff;
            --sidebar-text: #e8eee2;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.3);
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Alexandria', Arial, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            overflow-x: hidden;
            margin: 0;
            padding: 0;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .animate-fadeInUp { animation: fadeInUp 0.5s ease-out both; }
        .animate-fadeIn { animation: fadeIn 0.4s ease-out both; }
        .animate-slideInRight { animation: slideInRight 0.4s ease-out both; }

        /* ========== LAYOUT ========== */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            width: 252px;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            padding: 0;
            position: fixed;
            height: 100vh;
            right: 0;
            top: 0;
            z-index: 100;
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: -4px 0 30px rgba(0, 0, 0, 0.15);
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 10px; }

        /* Logo Section */
        .logo-section {
            text-align: center;
            padding: 20px 18px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(0, 0, 0, 0.1);
        }

        .logo-img {
            width: 86px;
            height: 66px;
            object-fit: contain;
            border-radius: 18px;
            margin-bottom: 10px;
            background: #fffdf9;
            padding: 5px 9px;
            transition: transform 0.3s ease;
        }

        .logo-img:hover {
            transform: scale(1.08);
        }

        .logo-title {
            display: none;
        }

        .logo-subtitle {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.72);
            margin-top: 4px;
            font-weight: 500;
        }

        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: linear-gradient(135deg, rgba(107,155,58,0.3), rgba(107,155,58,0.15));
            color: #b8d68e;
            font-size: 10px;
            font-weight: 700;
            padding: 5px 14px;
            border-radius: 20px;
            border: 1px solid rgba(107,155,58,0.3);
            margin-top: 10px;
            letter-spacing: 0.3px;
        }

        /* Sidebar Menu */
        .sidebar-nav {
            flex: 1;
            padding: 16px 14px;
        }

        .menu-label {
            font-size: 9px;
            font-weight: 800;
            color: rgba(255, 255, 255, 0.5);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 12px 16px 6px;
            margin-top: 4px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 11px 16px;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.82);
            border-radius: 14px;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 3px;
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.08), transparent);
            opacity: 0;
            transition: opacity 0.25s ease;
            border-radius: 14px;
        }

        .menu-item i {
            width: 22px;
            font-size: 15px;
            margin-left: 12px;
            text-align: center;
            color: rgba(255,255,255,0.4);
            transition: all 0.25s ease;
        }

        .menu-item:hover {
            color: #ffffff;
            transform: translateX(-3px);
        }

        .menu-item:hover::before { opacity: 1; }

        .menu-item:hover i { color: var(--accent-gold); }

        .menu-item.active {
            background: linear-gradient(135deg, var(--primary-color), #5a8a32);
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 4px 16px rgba(107,155,58,0.35), inset 0 1px 0 rgba(255,255,255,0.15);
        }

        .menu-item.active i { color: #ffffff; }

        .sidebar-badge {
            margin-right: auto;
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 9px;
            border-radius: 10px;
            backdrop-filter: blur(4px);
            min-width: 20px;
            text-align: center;
        }

        .menu-item.active .sidebar-badge {
            background: rgba(255,255,255,0.25);
            color: #ffffff;
        }

        /* Support Card */
        .sidebar-footer {
            padding: 0 14px 16px;
        }

        .support-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03));
            border-radius: 16px;
            padding: 18px;
            text-align: center;
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(8px);
        }

        .support-card .support-icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-warm));
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(201, 168, 76, 0.3);
        }

        .support-card p {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.6);
            line-height: 1.6;
            margin: 0 0 12px;
        }

        .contact-btn {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.06));
            color: #ffffff;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
            padding: 9px 12px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: all 0.25s ease;
        }

        .contact-btn:hover {
            background: rgba(255,255,255,0.18);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .logout-form {
            margin-top: 10px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255,255,255,0.45);
            padding: 10px 14px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            font-family: inherit;
        }

        .logout-btn:hover {
            background: rgba(220, 38, 38, 0.15);
            border-color: rgba(220, 38, 38, 0.3);
            color: #fca5a5;
        }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            flex: 1;
            margin-right: 252px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: var(--bg-color);
            transition: margin-right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ========== TOP HEADER ========== */
        .top-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 12px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .header-page-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-page-info .page-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            letter-spacing: 0.5px;
        }

        .header-page-info .page-title {
            font-size: 14px;
            font-weight: 900;
            color: var(--text-main);
            margin: 0;
        }

        .header-logo-sm {
            width: 32px;
            height: 32px;
            object-fit: contain;
            display: none;
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
            background: var(--glass-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 700;
            color: var(--text-main);
            text-decoration: none;
            transition: all 0.25s ease;
            font-family: inherit;
            cursor: pointer;
        }

        .header-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(107,155,58,0.15);
        }

        .tier-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
            border: 1px solid;
        }

        .tier-1 { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
        .tier-2 { background: #ecfdf5; color: #065f46; border-color: #6ee7b7; }
        .tier-3 { background: #fffbeb; color: #92400e; border-color: #fbbf24; }

        .notification-icon-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--glass-bg);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            font-size: 16px;
            text-decoration: none;
            transition: all 0.25s ease;
        }

        .notification-icon-btn:hover {
            background: var(--primary-light);
            border-color: var(--primary-color);
            color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .notification-badge-dot {
            position: absolute;
            top: -4px;
            left: -4px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #ffffff;
            font-size: 9px;
            font-weight: 900;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            box-shadow: 0 2px 6px rgba(220,38,38,0.4);
            animation: pulse-soft 2s ease-in-out infinite;
        }

        .user-info-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            padding-right: 12px;
            border-right: 1px solid var(--border-color);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: linear-gradient(135deg, #2a3f1a, #3b5728);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
            box-shadow: 0 2px 8px rgba(42, 63, 26, 0.25);
        }

        .user-info-btn .user-name {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-main);
        }

        .mobile-menu-btn {
            display: none;
            background: transparent;
            border: none;
            font-size: 20px;
            color: var(--text-main);
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

        /* ========== CONTENT BODY ========== */
        .content-body {
            flex: 1;
            padding: 28px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            box-sizing: border-box;
            animation: fadeInUp 0.5s ease-out;
        }

        /* Unified provider workspace */
        .main-content {
            background:
                radial-gradient(circle at 10% 4%, rgba(113,130,86,.10), transparent 24rem),
                linear-gradient(180deg, #faf9f6 0%, var(--bg-color) 42%, #f4f2ec 100%);
        }

        .content-body > div > div:has(> h1:first-child),
        .content-body > div > div:has(> div > h1:first-child) {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            padding: 22px 24px;
            border: 1px solid #dfe5d7;
            border-radius: 24px;
            background: linear-gradient(125deg, rgba(255,255,255,.98), rgba(242,247,237,.94));
            box-shadow: 0 12px 32px rgba(42,63,26,.08);
        }

        .content-body > div > div:has(> h1:first-child)::after,
        .content-body > div > div:has(> div > h1:first-child)::after {
            content: '';
            position: absolute;
            z-index: -1;
            left: -38px;
            bottom: -65px;
            width: 170px;
            height: 170px;
            border: 30px solid rgba(107,155,58,.08);
            border-radius: 50%;
        }

        .content-body > div > div:has(> h1:first-child) h1,
        .content-body > div > div:has(> div > h1:first-child) h1 {
            letter-spacing: -.025em;
        }

        .content-body [class~='rounded-3xl'][class~='border'][class~='bg-white'] {
            box-shadow: 0 8px 24px rgba(42,63,26,.055);
        }

        .content-body [class~='rounded-3xl'][class~='border'][class~='bg-white']:hover {
            border-color: #cad6be;
            box-shadow: 0 14px 34px rgba(42,63,26,.09);
        }

        .content-body a:focus-visible,
        .content-body button:focus-visible,
        .content-body input:focus-visible,
        .content-body select:focus-visible,
        .content-body textarea:focus-visible,
        .sidebar a:focus-visible,
        .top-header a:focus-visible,
        .top-header button:focus-visible {
            outline: 3px solid rgba(107,155,58,.35);
            outline-offset: 3px;
        }

        /* ========== COMPACT SIDEBAR ========== */
        .sidebar{width:224px;scrollbar-width:auto;scrollbar-color:rgba(255,255,255,.3) transparent}
        .sidebar::-webkit-scrollbar{width:6px}
        .sidebar::-webkit-scrollbar-thumb{background:rgba(255,255,255,.3);border-radius:999px}
        .main-content{margin-right:224px}
        .logo-section{padding:12px 14px 10px}
        .logo-img{width:72px;height:52px;margin-bottom:4px;border-radius:12px;padding:3px 7px}
        .logo-subtitle{max-width:185px;margin:2px auto 0;font-size:9px;line-height:1.55}
        .role-pill{margin-top:6px;padding:4px 10px;font-size:9px}
        .sidebar-nav{padding:7px 10px}
        .menu-label{margin-top:2px;padding:7px 10px 3px;font-size:8px;letter-spacing:.6px}
        .menu-item{min-height:35px;margin-bottom:1px;padding:8px 11px;border-radius:10px;font-size:11.5px}
        .menu-item i{width:18px;margin-left:9px;font-size:13px}
        .sidebar-badge{min-width:19px;height:19px;font-size:9px}
        .sidebar-footer{padding:0 10px 9px}
        .support-card{display:grid;grid-template-columns:28px 1fr;align-items:center;gap:7px;padding:9px;border-radius:12px;text-align:right}
        .support-card .support-icon{width:28px;height:28px;margin:0;border-radius:8px;font-size:12px}
        .support-card p{margin:0;font-size:9px;line-height:1.55}
        .contact-btn{grid-column:1/-1;margin-top:1px;padding:6px;font-size:9px;border-radius:8px;text-align:center}
        .logout-form{margin-top:6px}
        .logout-btn{padding:7px 10px;border-radius:9px;font-size:10px}

        .content-body{max-width:1320px;padding:20px}
        .content-body .space-y-8> :not([hidden])~ :not([hidden]){margin-top:1.25rem}
        .content-body .space-y-6> :not([hidden])~ :not([hidden]){margin-top:1rem}
        .content-body [class~='p-8']{padding:1.35rem}
        .content-body [class~='p-6']{padding:1.1rem}
        .content-body [class~='p-5']{padding:.95rem}
        .content-body [class~='p-12']{padding:2rem}
        .content-body [class~='gap-8']{gap:1.25rem}
        .content-body [class~='gap-6']{gap:1rem}
        .top-header{padding:9px 20px}

        .page-scroll-tools{position:fixed;bottom:16px;left:16px;z-index:80;display:grid;gap:5px;padding:5px;border:1px solid #dbe3d3;border-radius:14px;background:rgba(255,255,255,.92);box-shadow:0 8px 24px rgba(42,63,26,.14);backdrop-filter:blur(10px)}
        .page-scroll-tools button{display:grid;width:32px;height:32px;place-items:center;border:0;border-radius:9px;background:#f2f6ee;color:#385126;font-size:12px;cursor:pointer;transition:.2s}
        .page-scroll-tools button:hover{background:#31421e;color:#fff}

        @media(max-height:720px) and (min-width:1025px){
            .support-card{display:none}
            .logo-subtitle{display:none}
            .logo-section{padding-block:9px 7px}
            .role-pill{margin-top:3px}
        }

        /* ========== RESPONSIVE ========== */
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
            .header-logo-sm {
                display: block;
            }
            .content-body {
                padding: 16px;
            }
            .top-header {
                padding: 10px 16px;
            }
            .content-body > div > div:has(> h1:first-child),
            .content-body > div > div:has(> div > h1:first-child) {
                padding: 18px;
                border-radius: 20px;
            }
        }

        @media (max-width: 640px) {
            .content-body {
                padding: 12px;
            }
            .header-actions .tier-badge,
            .header-actions .user-info-btn {
                display: none;
            }
        }

        /* ========== PROVIDER COMPACT WORKSPACE ========== */
        @media (min-width: 1025px) {
            .sidebar { width: 200px; }
            .main-content { margin-right: 200px; }

            .logo-section { padding: 9px 12px 8px; }
            .logo-img { width: 62px; height: 44px; margin-bottom: 2px; }
            .logo-subtitle { display: none; }
            .role-pill { margin-top: 4px; padding: 3px 9px; font-size: 8px; }
            .sidebar-nav { padding: 5px 8px; }
            .menu-label { padding: 6px 9px 2px; font-size: 7.5px; }
            .menu-item { min-height: 31px; padding: 6px 9px; font-size: 10.5px; border-radius: 9px; }
            .menu-item i { width: 16px; margin-left: 7px; font-size: 11px; }
            .sidebar-footer { padding: 0 8px 7px; }
            .support-card { display: none; }
            .logout-form { margin-top: 4px; }
            .logout-btn { padding: 6px 8px; font-size: 9px; }

            .top-header { min-height: 54px; padding: 7px 16px; }
            .header-page-info { gap: 8px; }
            .header-page-info .page-label { font-size: 8px; }
            .header-page-info .page-title { font-size: 12px; }
            .header-actions { gap: 7px; }
            .tier-badge { padding: 4px 9px; font-size: 9px; }
            .header-btn { padding: 6px 10px; font-size: 10px; border-radius: 10px; }
            .notification-icon-btn { width: 34px; height: 34px; border-radius: 10px; font-size: 13px; }
            .user-info-btn { gap: 6px; padding-right: 8px; }
            .user-avatar { width: 30px; height: 30px; border-radius: 9px; font-size: 11px; }
            .user-info-btn .user-name { max-width: 120px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 10px; }

            .content-body { max-width: 1500px; padding: 14px 16px 20px; }
            .content-body > .space-y-8 > :not([hidden]) ~ :not([hidden]),
            .content-body .space-y-8 > :not([hidden]) ~ :not([hidden]) { margin-top: .9rem; }
            .content-body > .space-y-6 > :not([hidden]) ~ :not([hidden]),
            .content-body .space-y-6 > :not([hidden]) ~ :not([hidden]) { margin-top: .75rem; }
            .content-body .gap-8 { gap: .9rem; }
            .content-body .gap-6 { gap: .75rem; }
            .content-body .gap-4 { gap: .65rem; }
            .content-body .rounded-3xl { border-radius: 1rem; }
            .content-body .rounded-2xl { border-radius: .75rem; }
            .content-body .p-8,
            .content-body .sm\:p-8 { padding: 1rem; }
            .content-body .p-6,
            .content-body .sm\:p-6 { padding: .9rem; }
            .content-body .p-5 { padding: .8rem; }
            .content-body .p-4 { padding: .7rem; }
            .content-body .p-12 { padding: 1.5rem; }
            .content-body h1.text-2xl,
            .content-body h1.sm\:text-3xl { font-size: 1.35rem; line-height: 1.35; }
            .content-body h2.text-xl { font-size: 1rem; }
            .content-body h2.text-2xl,
            .content-body h2.sm\:text-3xl { font-size: 1.35rem; }
            .content-body .text-4xl,
            .content-body .sm\:text-5xl { font-size: 2rem; }
            .content-body .text-3xl { font-size: 1.55rem; }
            .content-body .h-12 { height: 2.5rem; }
            .content-body .w-12 { width: 2.5rem; }
            .content-body .h-10 { height: 2.15rem; }
            .content-body .w-10 { width: 2.15rem; }
            .content-body input,
            .content-body select,
            .content-body textarea,
            .content-body button,
            .content-body a { scroll-margin-top: 70px; }
        }

        /* ========== PROVIDER UX SYSTEM ========== */
        .provider-page,
        .provider-dashboard { --panel-radius: 16px; --panel-border: #e3e7df; }

        .provider-page-toolbar {
            min-height: 58px;
            padding: 10px 14px;
            border: 1px solid var(--panel-border);
            border-radius: var(--panel-radius);
            background: rgba(255,255,255,.88);
            box-shadow: 0 4px 14px rgba(42,63,26,.04);
        }
        .provider-page-toolbar h1 { display: none; }
        .provider-page-toolbar p { max-width: 680px; margin-top: 2px !important; font-size: .68rem !important; line-height: 1.55; }
        .provider-page-toolbar a { border-radius: 10px !important; padding: 8px 12px !important; }
        .provider-page-intro { display: none; }
        .provider-onboarding { display: none; }

        .provider-filter-bar { padding: 10px !important; border-radius: var(--panel-radius) !important; box-shadow: none !important; }
        .provider-filter-bar form { gap: 8px !important; }
        .provider-filter-bar input,
        .provider-filter-bar select,
        .provider-filter-bar button,
        .provider-filter-bar a { min-height: 38px; border-radius: 10px !important; }

        .provider-request-grid { gap: 12px !important; }
        .provider-request-card { padding: 14px !important; border-radius: var(--panel-radius) !important; box-shadow: 0 3px 12px rgba(30,42,22,.035) !important; }
        .provider-request-card > div:first-child > div:first-child { padding-bottom: 10px !important; }
        .provider-request-card > div:first-child > p { margin-top: 10px !important; line-height: 1.65 !important; }
        .provider-request-card .provider-privacy-note { display: none; }
        .provider-request-card > div:last-child { margin-top: 12px !important; padding-top: 10px !important; }
        .provider-request-card button { border-radius: 9px !important; }

        .provider-task-tabs {
            gap: 5px !important;
            padding: 5px !important;
            border: 1px solid var(--panel-border) !important;
            border-radius: 13px;
            background: #fff;
        }
        .provider-task-tabs a { padding: 7px 11px !important; border: 0 !important; border-radius: 9px !important; }
        .provider-task-list { gap: 12px !important; }
        .provider-task-card { padding: 14px !important; gap: 12px !important; border-radius: var(--panel-radius) !important; box-shadow: 0 3px 12px rgba(30,42,22,.035) !important; }
        .provider-task-card > div:first-child { padding-bottom: 10px !important; }
        .provider-task-progress {
            display: block !important;
            padding: 3px 12px 0;
        }
        .provider-task-progress .h-8.w-8 {
            width: 24px !important;
            height: 24px !important;
            font-size: 9px !important;
            box-shadow: 0 0 0 3px #fff;
        }
        .provider-task-progress .h-1 { height: 3px !important; }
        .provider-task-progress span.mt-2 {
            margin-top: 5px !important;
            font-size: 9px !important;
            white-space: nowrap;
        }
        .provider-task-card > .grid { gap: 8px !important; }
        .provider-task-card > .grid > div { padding: 10px !important; border-radius: 11px !important; line-height: 1.55 !important; }
        .provider-task-card > div:last-child { padding-top: 10px !important; }
        .provider-task-card button,
        .provider-task-card a { border-radius: 9px !important; padding-top: 8px !important; padding-bottom: 8px !important; }

        .provider-availability-grid { gap: 12px !important; align-items: start; }
        .provider-availability-grid > div { padding: 16px !important; border-radius: var(--panel-radius) !important; box-shadow: none !important; }
        .provider-availability-grid label { padding: 11px !important; gap: 10px !important; border-width: 1px !important; border-radius: 12px !important; }
        .provider-availability-grid label p { line-height: 1.55 !important; }
        .provider-availability-grid > div:last-child .space-y-3\.5 { display: grid; gap: 7px; }
        .provider-availability-grid > div:last-child .space-y-3\.5 > div { padding: 10px !important; border-radius: 11px !important; }
        .provider-status-reference { margin-top: 12px !important; }
        .provider-status-reference summary::-webkit-details-marker { display: none; }
        .provider-status-reference[open] summary .fa-chevron-down { transform: rotate(180deg); }
        .provider-status-reference summary .fa-chevron-down { transition: transform .2s ease; }

        .provider-performance-summary,
        .provider-certificates-summary { padding: 16px 18px !important; border-radius: var(--panel-radius) !important; box-shadow: 0 8px 22px rgba(36,53,22,.12) !important; }
        .provider-performance-summary > div,
        .provider-certificates-summary > div { gap: 14px !important; }
        .provider-performance-summary h2,
        .provider-certificates-summary h2 { margin-top: 7px !important; font-size: 1.25rem !important; }
        .provider-performance-summary p,
        .provider-certificates-summary p { margin-top: 4px !important; line-height: 1.6 !important; }
        .provider-performance-summary > div > div:last-child,
        .provider-certificates-summary > div > div:last-child { padding: 12px !important; }
        .provider-performance-stats { gap: 10px !important; }
        .provider-performance-stats > div { min-height: 112px; padding: 13px !important; border-radius: var(--panel-radius) !important; box-shadow: none !important; }
        .provider-performance-stats > div > .mt-5 { display: none; }
        .provider-performance-stats > div .mt-4 { margin-top: 8px !important; }
        .provider-review-list > div { padding: 13px !important; border-radius: var(--panel-radius) !important; box-shadow: none !important; }
        .provider-certificate-grid { gap: 12px !important; }
        .provider-certificate-grid > div { padding: 16px !important; gap: 12px !important; border-width: 1px !important; border-radius: var(--panel-radius) !important; box-shadow: none !important; }
        .provider-certificate-grid .mt-6 { margin-top: 12px !important; }
        .provider-certificate-grid .mt-6 > p.text-xs { display: none; }

        @media (min-width: 1180px) {
            .provider-request-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .provider-availability-grid { grid-template-columns: minmax(0, 1.1fr) minmax(0, .9fr); }
        }

        @media (max-width: 640px) {
            .provider-task-progress { display: none !important; }
            .provider-page-toolbar { align-items: stretch; padding: 11px; }
            .provider-page-toolbar a { justify-content: center; }
            .provider-performance-summary,
            .provider-certificates-summary { padding: 14px !important; }
        }
    </style>
</head>
<body x-data="{ mobileSidebarOpen: false }">
    @php
        $user = Auth::user();
        $providerProfile = $user?->serviceProviderProfile;
        $providerDisplayName = trim(preg_replace('/\s*\((?:مقدم خدمة|متطوع)\)\s*/u', '', $user?->name ?? ''));
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

    {{-- Backdrop للموبايل --}}
    <div class="sidebar-backdrop" :class="{ 'mobile-open': mobileSidebarOpen }" @click="mobileSidebarOpen = false"></div>

    <div class="dashboard-container">
        {{-- ===== الشريط الجانبي (Sidebar) ===== --}}
        <aside class="sidebar" :class="{ 'mobile-open': mobileSidebarOpen }">
            {{-- اللوغو --}}
            <div class="logo-section">
                <img src="{{ asset('assets/img/anees-logo.png') }}" alt="شعار منصة أنيس" class="logo-img">
                <h1 class="logo-title">أنيس</h1>
                <p class="logo-subtitle">منصة ربط طالبي المساعدة بمقدمي الخدمة</p>
                <span class="role-pill">
                    <i class="fa-solid fa-shield-check" style="font-size:10px"></i>
                    مقدم خدمة متطوع
                </span>
            </div>

            {{-- القائمة --}}
            <nav class="sidebar-nav">
                <div class="menu-label">القائمة الرئيسية</div>

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

                <div class="menu-label">الأداء والتطوير</div>

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

                <div class="menu-label">الإعدادات</div>

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

            {{-- الفوتر --}}
            <div class="sidebar-footer">
                <div class="support-card">
                    <div class="support-icon"><i class="fa-regular fa-comments" aria-hidden="true"></i></div>
                    <p>نحن هنا لمساعدتك<br>فريق الدعم متاح دائماً</p>
                    <a href="mailto:support@anees.app" class="contact-btn">تواصل معنا</a>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span>تسجيل خروج</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ===== المحتوى الرئيسي ===== --}}
        <main class="main-content">
            {{-- الهيدر العلوي --}}
            <header class="top-header">
                <div class="header-page-info">
                    <button type="button" class="mobile-menu-btn" @click="mobileSidebarOpen = !mobileSidebarOpen">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <img src="{{ asset('assets/img/anees-logo.png') }}" alt="شعار منصة أنيس" class="header-logo-sm">
                    <div>
                        <span class="page-label">بوابة مقدم الخدمة</span>
                        <h2 class="page-title">
                            {{ match(true) {
                                request()->routeIs('provider.dashboard') => 'لوحة التحكم والمتابعة',
                                request()->routeIs('provider.available') => 'فرص المساعدة والطلبات المتاحة',
                                request()->routeIs('provider.tasks*') => 'سجل ومتابعة المهام',
                                request()->routeIs('provider.performance') => 'مستوى الأداء والتقييم',
                                request()->routeIs('provider.certificates*') => 'شهادات وساعات التطوع الرقمية',
                                request()->routeIs('provider.availability') => 'إعدادات التوفر والخدمة',
                                request()->routeIs('notifications.*') => 'مركز الإشعارات والتنبيهات',
                                request()->routeIs('profile.*') => 'الملف الشخصي والإعدادات',
                                default => 'منصة أنيس'
                            } }}
                        </h2>
                    </div>
                </div>

                <div class="header-actions">
                    {{-- شارة المستوى --}}
                    <span class="tier-badge {{ $tier === 3 ? 'tier-3' : ($tier === 2 ? 'tier-2' : 'tier-1') }}">
                        <i class="fa-solid fa-trophy" aria-hidden="true"></i>
                        <span>المستوى {{ $tier }}</span>
                    </span>

                    {{-- زر التوفر السريع --}}
                    <form method="POST" action="{{ route('provider.availability.update') }}" class="hidden sm:inline">
                        @csrf
                        <input type="hidden" name="is_available" value="{{ $isAvailable ? '0' : '1' }}">
                        <button type="submit" class="header-btn" title="اضغط لتغيير حالة التوفر سريعاً">
                            <span class="h-2 w-2 rounded-full {{ $isAvailable ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}" style="display:inline-block;width:8px;height:8px;border-radius:50%;background:{{ $isAvailable ? '#10b981' : '#ef4444' }}"></span>
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

                    {{-- معلومات المستخدم --}}
                    <a href="{{ route('profile.edit') }}" class="user-info-btn hidden md:flex">
                        <div class="user-avatar">
                            {{ mb_substr($user->name, 0, 1) }}
                        </div>
                        <span class="user-name">{{ $providerDisplayName }}</span>
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

    {{-- مودالات مقدم الخدمة --}}
    <div class="page-scroll-tools" aria-label="التنقل السريع في الصفحة">
        <button type="button" onclick="window.scrollTo({ top: 0, behavior: 'smooth' })" title="أعلى الصفحة" aria-label="أعلى الصفحة">
            <i class="fa-solid fa-chevron-up" aria-hidden="true"></i>
        </button>
        <button type="button" onclick="window.scrollTo({ top: document.documentElement.scrollHeight, behavior: 'smooth' })" title="أسفل الصفحة" aria-label="أسفل الصفحة">
            <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
        </button>
    </div>
    @include('provider.partials.modals')
</body>
</html>
