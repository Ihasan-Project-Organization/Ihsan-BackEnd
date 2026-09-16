<x-app-layout>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --sidebar-bg: #354e20;
            --primary-color: #83a55b;
            --primary-light: #e6edd9;
            --text-main: #333333;
            --text-muted: #718096;
            --border-color: #e2e8f0;
            --danger: #e02424;
            --warning: #f6ad55;
        }

        .requests-header-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            background: #ffffff;
            padding: 24px 28px;
            border-radius: 20px;
            border: 1px solid #e7e2da;
            box-shadow: 0 2px 8px rgba(45, 74, 34, 0.04);
        }

        .requests-title-area h1 {
            font-size: 26px;
            font-weight: 900;
            color: var(--sidebar-bg);
            margin: 4px 0 6px;
        }

        .requests-title-area h4 {
            font-size: 12px;
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
        }

        .requests-title-area p {
            font-size: 13px;
            color: var(--text-muted);
            margin: 0;
        }

        .requests-action-area {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .new-request-btn {
            background-color: var(--sidebar-bg);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(53, 78, 32, 0.2);
            transition: all 0.2s ease;
        }

        .new-request-btn:hover {
            background-color: #263817;
            transform: translateY(-1px);
        }

        /* Tabs Container */
        .tabs-container {
            margin-bottom: 24px;
            border-bottom: 1.5px solid #e7e2da;
            padding-bottom: 4px;
        }

        .tabs {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            white-space: nowrap;
        }

        .tab-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 14px;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s ease;
            background: transparent;
        }

        .tab-btn:hover {
            background: #eef2e8;
            color: var(--sidebar-bg);
        }

        .tab-btn.active {
            background-color: var(--sidebar-bg);
            color: #ffffff;
            box-shadow: 0 3px 10px rgba(53, 78, 32, 0.15);
        }

        .tab-count {
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 800;
            background: rgba(0, 0, 0, 0.08);
            color: inherit;
        }

        .tab-btn.active .tab-count {
            background: rgba(255, 255, 255, 0.22);
            color: #ffffff;
        }

        .tab-count.alert-count {
            background: #e02424;
            color: #ffffff;
        }

        /* Cards Layout */
        .requests-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* 1. Active Request Card */
        .active-request-card {
            position: relative;
            background: #ffffff;
            border: 1px solid #e7e2da;
            border-radius: 18px;
            padding: 22px 26px;
            box-shadow: 0 2px 8px rgba(45, 74, 34, 0.04);
            transition: all 0.2s ease;
        }

        .active-request-card:hover {
            box-shadow: 0 6px 18px rgba(45, 74, 34, 0.08);
            border-color: #dce6c9;
        }

        .confirmed-card { border-color: #c9e29a; border-right: 5px solid var(--primary-color); }
        .in-progress-card { border-color: #dce6c9; border-right: 5px solid #8b5cf6; }

        .request-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
        }

        .request-card-top {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 14px;
        }

        .request-card-top h3 {
            margin: 0 0 4px;
            color: var(--sidebar-bg);
            font-size: 17px;
            font-weight: 800;
        }

        .request-card-top .request-id-text {
            color: var(--text-muted);
            font-size: 11px;
            font-family: monospace;
            font-weight: bold;
        }

        .request-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 12px;
            background: #eef2e8;
            color: var(--sidebar-bg);
            font-size: 20px;
            flex-shrink: 0;
        }

        .request-state {
            position: static;
            flex: 0 0 auto;
            max-width: 48%;
            padding: 5px 12px;
            border-radius: 14px;
            font-size: 11px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .request-state.waiting { background: #f2f2ed; color: #5d5d51; }
        .request-state.confirmed { background: #e9f5c7; color: #435b0e; }
        .request-state.progress { background: #f3e8ff; color: #6b21a8; }
        .request-state.pending-confirm { background: #fff7ed; color: #c2410c; }
        .request-state.under-review { background: #ffe4e6; color: #be123c; }

        .request-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 18px;
            margin: 14px 0 14px;
            color: var(--text-muted);
            font-size: 12px;
            font-weight: 600;
        }

        .request-meta span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .request-meta i {
            color: var(--primary-color);
            font-size: 13px;
        }

        .request-description {
            margin: 0 0 16px;
            color: #4a5568;
            font-size: 14px;
            line-height: 1.6;
        }

        /* 2. Action Required Card */
        .action-required-card {
            position: relative;
            overflow: hidden;
            background: #ffffff;
            border: 1px solid #fbd5d5;
            border-radius: 18px;
            padding: 24px 28px;
            box-shadow: 0 3px 12px rgba(220, 38, 38, 0.04);
        }

        .action-card-accent {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 6px;
            background: #e02424;
        }

        .action-card-heading {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .request-lock {
            flex: 0 0 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            background: #fee2e2;
            color: #b91c1c;
            font-size: 15px;
        }

        .action-request-info { flex: 1; }

        .action-request-info h2 {
            margin: 2px 0 5px;
            color: #1f2937;
            font-size: 17px;
            font-weight: 800;
        }

        .action-request-info p {
            color: #6b7280;
            font-size: 13px;
            margin: 0;
        }

        .action-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: 12px;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #b91c1c;
            margin: 14px 0;
        }

        .action-alert i { font-size: 18px; }
        .action-alert strong { display: block; font-size: 12px; }
        .action-alert p { margin-top: 2px; font-size: 11px; color: #7f1d1d; }

        /* 3. Completed Request Card */
        .completed-request-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(45, 74, 34, 0.04);
        }

        .completed-card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
        }

        .completed-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .completed-title h2 {
            color: #1f2937;
            font-size: 16px;
            font-weight: 800;
            margin: 0 0 4px;
        }

        .completed-title p {
            color: var(--text-muted);
            font-size: 12px;
            margin: 0;
        }

        .completed-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .completed-icon.green { background: #d7ee8d; color: #354e20; }

        .completed-badge {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
            border-radius: 14px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .completed-meta {
            display: flex;
            gap: 20px;
            margin-top: 14px;
            padding: 11px 16px;
            border-radius: 12px;
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
        }

        .rate-button {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: none;
            border-radius: 12px;
            padding: 9px 18px;
            background: #2b4400;
            color: #ffffff;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }

        .rate-button:hover {
            background: #1b2d00;
        }

        .rated-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 12px;
            padding-top: 10px;
            border-top: 1px solid #f1f5f9;
            color: var(--text-muted);
            font-size: 12px;
        }

        .stars { color: #f59e0b; font-size: 16px; letter-spacing: 2px; }

        /* 4. Cancelled Request Card */
        .cancelled-request-card {
            background: #ffffff;
            border: 1px solid #deded5;
            border-radius: 16px;
            padding: 18px 24px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
        }

        .cancelled-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .cancelled-request-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cancelled-icon {
            display: grid;
            flex: 0 0 38px;
            width: 38px;
            height: 38px;
            place-items: center;
            color: #66685d;
            background: #f0f0ee;
            border-radius: 50%;
            font-size: 16px;
        }

        .cancelled-request-info h2 {
            margin: 0 0 3px;
            color: #37372f;
            font-size: 14px;
            font-weight: 800;
        }

        .cancelled-status {
            padding: 5px 12px;
            color: #b91c1c;
            background: #fee2e2;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
        }

        /* Card Action Bar & Buttons */
        .card-actions-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            margin-top: 8px;
        }

        .action-button {
            min-height: 34px;
            padding: 7px 18px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            background: #ffffff;
            color: #374151;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .listen-status {
            border-color: #cfe0bf;
            background: #f2f7ed;
            color: #3f5d2b;
        }

        .listen-status:hover {
            border-color: #8eaa76;
            background: #e8f1df;
        }

        .action-button:hover {
            transform: translateY(-1px);
        }

        .schedule-and-repost {
            background: var(--sidebar-bg);
            border-color: var(--sidebar-bg);
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(53, 78, 32, 0.15);
        }

        .schedule-and-repost:hover {
            background: #253916;
            color: #ffffff;
        }

        .cancel-request {
            border-color: #fee2e2;
            background: #fff5f5;
            color: #dc2626;
        }

        .cancel-request:hover {
            background: #fee2e2;
            color: #b91c1c;
        }

        .phone-masked-notice {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #64748b;
            background: #f8fafc;
            padding: 6px 12px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .phone-masked-notice i {
            color: #94a3b8;
            font-size: 11px;
        }

        /* Visual Service Stepper لكبير السن */
        .service-stepper-wrapper {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 14px 18px;
            margin: 14px 0;
        }

        .stepper-header-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 800;
            color: #334155;
            margin-bottom: 14px;
        }

        .service-stepper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            gap: 6px;
        }

        .stepper-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            z-index: 2;
            flex: 1;
            text-align: center;
        }

        .stepper-icon-node {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 6px;
            transition: all 0.3s ease;
        }

        .stepper-step.completed .stepper-icon-node {
            background: #354e20;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(53, 78, 32, 0.2);
        }

        .stepper-step.active .stepper-icon-node {
            background: #83a55b;
            color: #ffffff;
            box-shadow: 0 0 0 4px rgba(131, 165, 91, 0.25), 0 4px 12px rgba(53, 78, 32, 0.25);
            transform: scale(1.1);
        }

        .stepper-step.upcoming .stepper-icon-node {
            background: #e2e8f0;
            color: #94a3b8;
        }

        .stepper-label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            line-height: 1.3;
        }

        .stepper-step.active .stepper-label {
            color: #263817;
            font-weight: 900;
            font-size: 12px;
        }

        .stepper-step.completed .stepper-label {
            color: #354e20;
        }

        .stepper-line {
            position: absolute;
            top: 18px;
            right: 12%;
            left: 12%;
            height: 3px;
            background: #e2e8f0;
            z-index: 1;
        }

        .stepper-line-progress {
            height: 100%;
            background: #83a55b;
            transition: width 0.4s ease;
        }

        /* Empty State */
        .requests-empty-state {
            background: #ffffff;
            border: 1px solid #e7e2da;
            border-radius: 20px;
            padding: 50px 20px;
            text-align: center;
        }

        .empty-icon {
            font-size: 42px;
            color: #cbd5e0;
            margin-bottom: 12px;
        }

        .requests-empty-state h3 {
            font-size: 18px;
            font-weight: 800;
            color: #2d3748;
            margin-bottom: 6px;
        }

        .requests-empty-state p {
            font-size: 13px;
            color: var(--text-muted);
            max-width: 450px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .requests-header-box {
                flex-direction: column;
                text-align: center;
                gap: 16px;
                padding: 20px;
            }
            .requests-action-area {
                width: 100%;
                flex-direction: column;
            }
            .new-request-btn {
                width: 100%;
                justify-content: center;
            }
            .request-state {
                position: static;
                margin-top: 8px;
                display: inline-block;
            }
            .card-actions-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .action-button {
                justify-content: center;
                width: 100%;
            }
        }

        /* Compact request page */
        .requests-header-box{margin-bottom:16px;padding:16px 18px;border-radius:16px}
        .requests-title-area h1{font-size:22px;margin:2px 0 4px}
        .requests-title-area p{font-size:12px}
        .requests-action-area{gap:8px}
        .new-request-btn{padding:8px 15px;border-radius:11px;font-size:12px}
        .tabs-container{margin-bottom:16px}
        .tabs{gap:6px}
        .tab-btn{gap:6px;padding:7px 13px;border-radius:11px;font-size:12px}
        .requests-list{gap:12px}
        .active-request-card{padding:16px 18px;border-radius:14px}
        .request-card-header{gap:10px}
        .request-card-top{gap:10px}
        .request-card-top h3{font-size:15px}
        .request-icon{width:38px;height:38px;border-radius:10px;font-size:17px}
        .request-state{max-width:50%;padding:4px 10px;font-size:10px}
        .request-meta{gap:13px;margin:10px 0;font-size:11px}
        .request-description{margin-bottom:12px;font-size:13px}
        .action-required-card{padding:16px 18px;border-radius:14px}
        .action-card-heading{gap:11px}
        .action-request-info h2{font-size:15px}
        .action-request-info p{font-size:12px}
        .action-alert{gap:9px;margin:10px 0;padding:9px 12px}
        .completed-request-card{padding:15px 18px;border-radius:14px}
        .completed-title h2{font-size:14px}
        .completed-icon{width:38px;height:38px;font-size:17px}
        .completed-meta{gap:14px;margin-top:10px;padding:8px 12px;font-size:11px}
        .cancelled-request-card{padding:14px 18px;border-radius:14px}
        .card-actions-bar{gap:8px;padding-top:11px}
        .action-button{min-height:31px;padding:6px 13px;border-radius:10px;font-size:11px}
        @media(max-width:640px){.request-card-header{align-items:stretch;flex-direction:column}.request-state{max-width:100%;align-self:flex-start}.card-actions-bar{align-items:stretch;flex-direction:column}.card-actions-bar>div{width:100%}.cancel-request{align-self:flex-start}}
    </style>

    <div class="space-y-6">

        {{-- تنبيهات العمليات والرسائل السريعة --}}
        @if (session('status') === 'request-created')
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>تم إنشاء ونشر طلب المساعدة بنجاح! سيتم إشعارك فور قبول أحد مقدمي الخدمة.</span>
            </div>
        @elseif (session('status') === 'request-rescheduled')
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <i class="fa-solid fa-arrows-rotate text-base"></i>
                <span>تم تحديد موعد جديد وإعادة نشر الطلب بنجاح بنفس الرقم وتاريخ المحاولات.</span>
            </div>
        @elseif (session('status') === 'request-updated')
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <i class="fa-solid fa-pen-to-square text-base"></i>
                <span>تم تعديل بيانات الطلب وإعادة نشره لمقدمي الخدمة.</span>
            </div>
        @elseif (session('status') === 'request-completed')
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 shadow-sm">
                <i class="fa-solid fa-star text-base text-amber-500"></i>
                <span>تم تأكيد اكتمال الخدمة بنجاح! شكرًا لك، يمكنك الآن تقييم مقدم الخدمة.</span>
            </div>
        @elseif (session('status') === 'request-cancelled')
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-100 p-4 text-sm font-bold text-slate-700 shadow-sm">
                <i class="fa-regular fa-circle-xmark text-base"></i>
                <span>تم إلغاء الطلب ونقله إلى قسم الطلبات الملغاة.</span>
            </div>
        @elseif (session('status') === 'request-reassigned')
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-4 text-sm font-bold text-blue-800 shadow-sm">
                <i class="fa-solid fa-magnifying-glass text-base"></i>
                <span>تم فك الإسناد وإعادة نشر الطلب للبحث عن مقدم خدمة بديل.</span>
            </div>
        @elseif (session('status') === 'review-submitted')
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm font-bold text-amber-800 shadow-sm">
                <i class="fa-solid fa-award text-base text-amber-500"></i>
                <span>شكرًا لك! تم إرسال تقييمك لمقدم الخدمة بنجاح.</span>
            </div>
        @elseif (session('status') === 'problem-reported')
            <div class="mb-5 flex items-center gap-3 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-bold text-rose-800 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-base"></i>
                <span>تم استلام بلاغ المشكلة بنجاح وإحالة الطلب إلى التدقيق الإداري.</span>
            </div>
        @endif

        {{-- رأس الصفحة مع البحث وزر الطلب الجديد من التصميم المعتمد --}}
        <div class="requests-header-box">
            <div class="requests-title-area">
                <h4>لوحة التحكم</h4>
                <h1>طلباتي</h1>
                <p>تابع/ي حالة جميع طلبات المساعدة والمرافقة</p>
            </div>

            <div class="requests-action-area">
                <form method="GET" action="{{ route('service-requests.index') }}" class="relative w-full sm:w-auto">
                    <input type="hidden" name="tab" value="{{ $tab }}">
                    <input type="text" name="search" value="{{ $search }}"
                        placeholder="ابحث برقم الطلب أو العنوان..."
                        class="rounded-xl border border-slate-200 bg-[#f8fafc] py-2 px-4 pr-9 text-xs focus:border-[#718256] focus:bg-white focus:ring-1 focus:ring-[#718256] w-full sm:w-60">
                    <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                </form>

                <button type="button" onclick="openCreateRequestModal()" class="new-request-btn">
                    <i class="fa-solid fa-plus"></i>
                    <span>طلب جديد</span>
                </button>
            </div>
        </div>

        {{-- التبويبات مع الروابط الفعلية والعدادات --}}
        <div class="tabs-container">
            <div class="tabs">
                <a href="{{ route('service-requests.index', ['tab' => 'all', 'search' => $search]) }}"
                    class="tab-btn {{ $tab === 'all' ? 'active' : '' }}">
                    <span>الكل</span>
                    <span class="tab-count">{{ $counts['all'] }}</span>
                </a>

                <a href="{{ route('service-requests.index', ['tab' => 'active', 'search' => $search]) }}"
                    class="tab-btn {{ $tab === 'active' ? 'active' : '' }}">
                    <span>نشطة</span>
                    <span class="tab-count">{{ $counts['active'] }}</span>
                </a>

                <a href="{{ route('service-requests.index', ['tab' => 'needs_action', 'search' => $search]) }}"
                    class="tab-btn {{ $tab === 'needs_action' ? 'active' : '' }}">
                    <span>بحاجة لإجراء</span>
                    <span class="tab-count {{ $counts['needs_action'] > 0 ? 'alert-count' : '' }}">{{ $counts['needs_action'] }}</span>
                </a>

                <a href="{{ route('service-requests.index', ['tab' => 'completed', 'search' => $search]) }}"
                    class="tab-btn {{ $tab === 'completed' ? 'active' : '' }}">
                    <span>مكتملة</span>
                    <span class="tab-count">{{ $counts['completed'] }}</span>
                </a>

                <a href="{{ route('service-requests.index', ['tab' => 'cancelled', 'search' => $search]) }}"
                    class="tab-btn {{ $tab === 'cancelled' ? 'active' : '' }}">
                    <span>ملغاة</span>
                    <span class="tab-count">{{ $counts['cancelled'] }}</span>
                </a>
            </div>
        </div>

        {{-- قائمة الطلبات مع البيانات الفعلية --}}
        <div class="requests-list">
            @forelse ($requests as $item)
                @php
                    $provPhone = $item->serviceProviderProfile?->phone_number;
                    $provName = $item->serviceProviderProfile?->full_name ?? $item->serviceProviderProfile?->user?->name ?? 'مقدم الخدمة';
                    $statusLabels = [
                        \App\Models\ServiceRequest::STATUS_PENDING_ACCEPTANCE => 'بانتظار قبول مقدم الخدمة',
                        \App\Models\ServiceRequest::STATUS_ACCEPTED => 'تم قبول الطلب',
                        \App\Models\ServiceRequest::STATUS_ASSIGNED => 'تم توكيل مقدم الخدمة',
                        \App\Models\ServiceRequest::STATUS_IN_PROGRESS => 'الطلب قيد التنفيذ',
                        \App\Models\ServiceRequest::STATUS_PENDING_CONFIRMATION => 'بانتظار تأكيد اكتمال الخدمة',
                        \App\Models\ServiceRequest::STATUS_COMPLETED => 'مكتمل',
                        \App\Models\ServiceRequest::STATUS_UNDER_REVIEW => 'قيد المراجعة الإدارية',
                        \App\Models\ServiceRequest::STATUS_NO_PROVIDER_FOUND => 'لم يتوفر مقدم خدمة',
                        \App\Models\ServiceRequest::STATUS_PROVIDER_APOLOGIZED => 'اعتذر مقدم الخدمة',
                        \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED => 'مقدم الخدمة متأخر',
                        \App\Models\ServiceRequest::STATUS_CANCELLED => 'ملغى',
                    ];
                    $spokenStatus = $statusLabels[$item->status] ?? 'قيد المتابعة';
                    $spokenRequest = "طلب {$item->title}. الحالة: {$spokenStatus}. الموعد: {$item->scheduled_at->translatedFormat('l d F Y، h:i A')}. المكان: {$item->location}. التفاصيل: {$item->description}.";
                @endphp

                {{-- ========================================================= --}}
                {{-- الحالات 7 و 8 و 9 و 10: بحاجة لإجراء / مشكلة (Action Required Cards) --}}
                {{-- ========================================================= --}}
                @if (in_array($item->status, [
                    \App\Models\ServiceRequest::STATUS_PROVIDER_APOLOGIZED,
                    \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED,
                    \App\Models\ServiceRequest::STATUS_NO_PROVIDER_FOUND,
                    \App\Models\ServiceRequest::STATUS_UNDER_REVIEW,
                ], true))

                    <article class="action-required-card">
                        <button type="button"
                            data-tts-text="{{ $spokenRequest }}"
                            data-voice-key="status_{{ $item->status }}"
                            class="float-left mb-3 inline-flex min-h-11 items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 text-sm font-black text-blue-800"
                            aria-label="قراءة حالة الطلب بصوت مرتفع">
                            <span aria-hidden="true">🔊</span>
                            <span>استمع للحالة</span>
                        </button>
                        <div class="action-card-accent"></div>
                        <div class="action-card-heading">
                            <span class="request-lock" aria-hidden="true"><i class="fa-solid fa-triangle-exclamation"></i></span>
                            <div class="action-request-info">
                                <span class="text-xs font-mono font-bold text-slate-400">{{ $item->public_id }}</span>
                                <h2>{{ $item->title }}</h2>
                                <p>{{ $item->description }}</p>
                            </div>
                        </div>

                        <div class="request-meta" style="margin-top: 10px;">
                            <span><i class="fa-regular fa-calendar"></i> {{ $item->scheduled_at->translatedFormat('l d F Y - h:i A') }}</span>
                            <span><i class="fa-solid fa-location-dot"></i> {{ $item->location }}</span>
                            @if ($item->serviceProviderProfile)
                                <span><i class="fa-solid fa-user"></i> المتطوع: {{ $provName }}</span>
                            @endif
                        </div>

                        {{-- صندوق التنبيه التفصيلي حسب الحالة --}}
                        @if ($item->status === \App\Models\ServiceRequest::STATUS_PROVIDER_APOLOGIZED)
                            <div class="action-alert">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <div>
                                    <strong>اعتذر مقدم الخدمة عن تنفيذ الطلب</strong>
                                    <p>تم فك إسناد الطلب وإعادته للبحث، يمكنك تحديد موعد جديد أو إلغاء الطلب.</p>
                                </div>
                            </div>
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED)
                            <div class="action-alert" style="background:#fffbeb;border-color:#fef3c7;color:#b45309;">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <div>
                                    <strong>تأخر مقدم الخدمة عن الموعد المحدد</strong>
                                    <p>يمكنك البحث عن بديل فوراً أو التواصل معه للاستفسار أو إلغاء الطلب.</p>
                                </div>
                            </div>
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_NO_PROVIDER_FOUND)
                            <div class="action-alert" style="background:#fffbeb;border-color:#fef3c7;color:#b45309;">
                                <i class="fa-solid fa-calendar-xmark"></i>
                                <div>
                                    <strong>لم يتوفر متطوع قبل حلول الموعد المحدد</strong>
                                    <p>يرجى اختيار موعد جديد مناسب لإعادة نشر الطلب لمقدمي الخدمة أو إلغائه.</p>
                                </div>
                            </div>
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_UNDER_REVIEW)
                            <div class="action-alert" style="background:#eff6ff;border-color:#dbeafe;color:#1d4ed8;">
                                <i class="fa-solid fa-shield-halved"></i>
                                <div>
                                    <strong>الطلب قيد المراجعة الإدارية</strong>
                                    <p>تم استلام بلاغك بنجاح ويتولى فريق الدعم والإشراف مراجعة التفاصيل ومتابعتها.</p>
                                </div>
                            </div>
                        @endif

                        {{-- أزرار الإجراءات --}}
                        <div class="card-actions-bar">
                            <div class="flex flex-wrap items-center gap-2">
                                {{-- إعادة الجدولة --}}
                                @if (in_array($item->status, [\App\Models\ServiceRequest::STATUS_PROVIDER_APOLOGIZED, \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED, \App\Models\ServiceRequest::STATUS_NO_PROVIDER_FOUND]))
                                    <button type="button"
                                        onclick="openRescheduleModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.reschedule', $item) }}')"
                                        class="action-button schedule-and-repost">
                                        <i class="fa-solid fa-calendar-plus"></i>
                                        <span>موعد جديد وإعادة النشر</span>
                                    </button>
                                @endif

                                {{-- البحث عن بديل عند التأخر --}}
                                @if ($item->status === \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED)
                                    <form method="POST" action="{{ route('service-requests.search-alternative', $item) }}" class="inline">
                                        @csrf
                                        @method('patch')
                                        <button type="submit" class="action-button" style="background:#3b5228;color:#fff;">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                            <span>البحث عن بديل فوراً</span>
                                        </button>
                                    </form>
                                @endif

                                {{-- منطق حجب رقم الهاتف الحرج (2.3) --}}
                                @if ($item->canRevealContactPhone() && $provPhone)
                                    <button type="button"
                                        onclick="openContactModal('{{ addslashes($provName) }}', '{{ $provPhone }}')"
                                        class="action-button">
                                        <i class="fa-solid fa-phone"></i>
                                        <span>تواصل مع المتطوع</span>
                                    </button>
                                @elseif ($item->status === \App\Models\ServiceRequest::STATUS_PROVIDER_DELAYED && !$item->canRevealContactPhone())
                                    <span class="phone-masked-notice">
                                        <i class="fa-solid fa-lock"></i>
                                        <span>سيظهر رقم التواصل بعد تأكيد الإسناد</span>
                                    </span>
                                @endif
                            </div>

                            {{-- منطق زر الإلغاء الحرج (2.2): ملفوف شرطياً بـ canBeCancelledByElderly() بدون استثناء --}}
                            @if ($item->canBeCancelledByElderly())
                                <button type="button"
                                    onclick="openCancelModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.cancel', $item) }}')"
                                    class="action-button cancel-request">
                                    <i class="fa-regular fa-circle-xmark"></i>
                                    <span>إلغاء الطلب</span>
                                </button>
                            @endif
                        </div>
                    </article>

                {{-- ========================================================= --}}
                {{-- الحالة 6: مكتملة (Completed Request Card) --}}
                {{-- ========================================================= --}}
                @elseif ($item->status === \App\Models\ServiceRequest::STATUS_COMPLETED)

                    <article class="completed-request-card">
                        <button type="button"
                            data-tts-text="{{ $spokenRequest }}"
                            data-voice-key="status_{{ $item->status }}"
                            class="float-left mb-3 inline-flex min-h-11 items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 text-sm font-black text-blue-800"
                            aria-label="قراءة حالة الطلب بصوت مرتفع">
                            <span aria-hidden="true">🔊</span>
                            <span>استمع للحالة</span>
                        </button>
                        <div class="completed-card-top">
                            <div class="completed-title">
                                <span class="completed-icon green"><i class="fa-solid fa-circle-check"></i></span>
                                <div>
                                    <h2>{{ $item->title }}</h2>
                                    <p>{{ $item->description }}</p>
                                </div>
                            </div>
                            <span class="completed-badge"><i class="fa-solid fa-check"></i> مكتمل</span>
                        </div>

                        <div class="completed-meta">
                            <span><i class="fa-regular fa-calendar"></i> <b>تاريخ الإنجاز:</b> {{ $item->completed_at?->translatedFormat('d F Y - h:i A') ?? $item->updated_at->translatedFormat('d F Y') }}</span>
                            @if ($item->serviceProviderProfile)
                                <span><i class="fa-solid fa-user"></i> <b>المتطوع:</b> {{ $provName }}</span>
                            @endif
                            <span class="font-mono text-slate-400 mr-auto">{{ $item->public_id }}</span>
                        </div>

                        {{-- قسم التقييم للخدمة المكتملة --}}
                        <div class="mt-4">
                            @if ($item->review)
                                <div class="rated-row">
                                    <span class="stars">{{ str_repeat('★', $item->review->stars) }}</span>
                                    <span>لقد قمت بتقييم هذه الخدمة ({{ $item->review->stars }}/5)</span>
                                </div>
                            @endif
                        </div>
                    </article>

                {{-- ========================================================= --}}
                {{-- الحالة 11: ملغاة (Cancelled Request Card) --}}
                {{-- ========================================================= --}}
                @elseif ($item->status === \App\Models\ServiceRequest::STATUS_CANCELLED)

                    <article class="cancelled-request-card">
                        <button type="button"
                            data-tts-text="{{ $spokenRequest }}"
                            data-voice-key="status_{{ $item->status }}"
                            class="float-left mb-3 inline-flex min-h-11 items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 text-sm font-black text-blue-800"
                            aria-label="قراءة حالة الطلب بصوت مرتفع">
                            <span aria-hidden="true">🔊</span>
                            <span>استمع للحالة</span>
                        </button>
                        <header class="cancelled-card-header">
                            <div class="cancelled-request-info">
                                <span class="cancelled-icon" aria-hidden="true"><i class="fa-regular fa-circle-xmark"></i></span>
                                <div>
                                    <h2>{{ $item->title }}</h2>
                                    <span class="text-xs text-slate-400 font-mono">{{ $item->public_id }}</span>
                                </div>
                            </div>
                            <span class="cancelled-status"><i class="fa-solid fa-xmark"></i> ملغاة</span>
                        </header>

                        <div class="mt-3 flex flex-wrap items-center justify-between text-xs text-slate-500 pt-3 border-t border-slate-100">
                            <span>تاريخ الإلغاء: {{ $item->cancelled_at?->translatedFormat('d F Y - h:i A') ?? $item->updated_at->translatedFormat('d F Y') }}</span>
                            @if ($item->cancellation_reason)
                                <span class="font-bold text-slate-600">سبب الإلغاء: {{ $item->cancellation_reason }}</span>
                            @endif
                        </div>
                    </article>

                {{-- ========================================================= --}}
                {{-- الحالات النشطة: 1 و 2 و 3 و 4 و 5 (Active Request Cards) --}}
                {{-- ========================================================= --}}
                @else

                    <article class="active-request-card {{ $item->status === \App\Models\ServiceRequest::STATUS_ASSIGNED ? 'confirmed-card' : '' }} {{ $item->status === \App\Models\ServiceRequest::STATUS_IN_PROGRESS ? 'in-progress-card' : '' }}">
                        <div class="request-card-header">
                            <div class="request-card-top">
                            <span class="request-icon">
                                @if (str_contains($item->title, 'دواء') || str_contains($item->description, 'دواء') || $item->service_type === 'medical')
                                    <i class="fa-solid fa-kit-medical"></i>
                                @elseif (str_contains($item->title, 'أغراض') || str_contains($item->title, 'شراء') || $item->service_type === 'grocery')
                                    <i class="fa-solid fa-cart-shopping"></i>
                                @elseif (str_contains($item->title, 'مرافقة') || $item->service_type === 'companion')
                                    <i class="fa-solid fa-person-walking"></i>
                                @elseif (str_contains($item->title, 'زيارة') || $item->service_type === 'social')
                                    <i class="fa-solid fa-users"></i>
                                @else
                                    <i class="fa-solid fa-handshake-angle"></i>
                                @endif
                            </span>
                            <div>
                                <h3>{{ $item->title }}</h3>
                                <span class="request-id-text">رقم الطلب: {{ $item->public_id }}</span>
                            </div>
                        </div>

                        {{-- شارة الحالة المعتمدة من الحالات الـ 11 --}}
                        @if ($item->status === \App\Models\ServiceRequest::STATUS_PENDING_ACCEPTANCE)
                            <span class="request-state waiting">
                                <span class="h-2 w-2 rounded-full bg-amber-400 animate-pulse"></span>
                                <span>بانتظار قبول مقدم الخدمة</span>
                            </span>
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_ACCEPTED || $item->status === \App\Models\ServiceRequest::STATUS_ASSIGNED)
                            <span class="request-state confirmed">
                                <i class="fa-solid fa-user-check"></i>
                                <span>تم توكيل مقدم الخدمة</span>
                            </span>
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_IN_PROGRESS)
                            <span class="request-state progress">
                                <i class="fa-solid fa-person-walking"></i>
                                <span>قيد التنفيذ الآن</span>
                            </span>
                        @elseif ($item->status === \App\Models\ServiceRequest::STATUS_PENDING_CONFIRMATION)
                            <span class="request-state pending-confirm">
                                <i class="fa-solid fa-clock"></i>
                                <span>أنهيت المهمة — بانتظار تأكيدك</span>
                            </span>
                        @endif
                        </div>

                        <div class="request-meta">
                            <span><i class="fa-regular fa-calendar"></i> {{ $item->scheduled_at->translatedFormat('l d F - h:i A') }}</span>
                            <span><i class="fa-solid fa-location-dot"></i> {{ $item->location }}</span>
                            @if ($item->serviceProviderProfile)
                                <span><i class="fa-solid fa-user"></i> المتطوع: {{ $provName }}</span>
                            @endif
                        </div>

                        <p class="request-description">{{ $item->description }}</p>

                        {{-- مسار الخدمة البصري التفاعلي لكبير السن --}}
                        @php
                            $currentStep = match($item->status) {
                                \App\Models\ServiceRequest::STATUS_PENDING_ACCEPTANCE => 1,
                                \App\Models\ServiceRequest::STATUS_ACCEPTED, \App\Models\ServiceRequest::STATUS_ASSIGNED => 2,
                                \App\Models\ServiceRequest::STATUS_IN_PROGRESS => 3,
                                \App\Models\ServiceRequest::STATUS_PENDING_CONFIRMATION, \App\Models\ServiceRequest::STATUS_COMPLETED => 4,
                                default => 1,
                            };
                            $progressPercent = match($currentStep) {
                                1 => 0,
                                2 => 33.3,
                                3 => 66.6,
                                4 => 100,
                            };
                        @endphp

                        <div class="service-stepper-wrapper">
                            <div class="stepper-header-title">
                                <i class="fa-solid fa-route text-emerald-700"></i>
                                <span>مسار الخدمة الحالي (مرحلة {{ $currentStep }} من 4):</span>
                            </div>
                            <div class="service-stepper">
                                <div class="stepper-line">
                                    <div class="stepper-line-progress" style="width: {{ $progressPercent }}%;"></div>
                                </div>

                                {{-- الخطوة 1 --}}
                                <div class="stepper-step {{ $currentStep > 1 ? 'completed' : ($currentStep === 1 ? 'active' : 'upcoming') }}">
                                    <div class="stepper-icon-node">
                                        @if ($currentStep > 1)
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            1
                                        @endif
                                    </div>
                                    <span class="stepper-label">نشر الطلب</span>
                                </div>

                                {{-- الخطوة 2 --}}
                                <div class="stepper-step {{ $currentStep > 2 ? 'completed' : ($currentStep === 2 ? 'active' : 'upcoming') }}">
                                    <div class="stepper-icon-node">
                                        @if ($currentStep > 2)
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            2
                                        @endif
                                    </div>
                                    <span class="stepper-label">تم التوكيل</span>
                                </div>

                                {{-- الخطوة 3 --}}
                                <div class="stepper-step {{ $currentStep > 3 ? 'completed' : ($currentStep === 3 ? 'active' : 'upcoming') }}">
                                    <div class="stepper-icon-node">
                                        @if ($currentStep > 3)
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            3
                                        @endif
                                    </div>
                                    <span class="stepper-label">قيد التنفيذ</span>
                                </div>

                                {{-- الخطوة 4 --}}
                                <div class="stepper-step {{ $currentStep === 4 ? 'active' : ($currentStep > 4 ? 'completed' : 'upcoming') }}">
                                    <div class="stepper-icon-node">
                                        @if ($currentStep >= 4 && $item->status === \App\Models\ServiceRequest::STATUS_COMPLETED)
                                            <i class="fa-solid fa-check"></i>
                                        @else
                                            4
                                        @endif
                                    </div>
                                    <span class="stepper-label">إنجاز الخدمة</span>
                                </div>
                            </div>
                        </div>

                        {{-- أشرطة الإجراءات والتواصل والإلغاء --}}
                        <div class="card-actions-bar">
                            <div class="flex flex-wrap items-center gap-2">
                                <button type="button"
                                    data-tts-text="{{ $spokenRequest }}"
                            data-voice-key="status_{{ $item->status }}"
                                    class="action-button listen-status"
                                    aria-label="قراءة حالة الطلب بصوت مرتفع">
                                    <i class="fa-solid fa-volume-high"></i>
                                    <span>استمع للحالة</span>
                                </button>

                                {{-- أزرار تأكيد الإنجاز أو الإبلاغ عن مشكلة للحالة pending_confirmation --}}
                                @if ($item->status === \App\Models\ServiceRequest::STATUS_PENDING_CONFIRMATION)
                                    <button type="button"
                                        onclick="openConfirmModal({{ $item->id }}, '{{ addslashes($provName) }}', '{{ route('service-requests.confirm', $item) }}')"
                                        class="action-button schedule-and-repost">
                                        <i class="fa-solid fa-check-double"></i>
                                        <span>تأكيد اكتمال الخدمة والتقييم</span>
                                    </button>
                                    <button type="button"
                                        onclick="openReportProblemModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.report-problem', $item) }}')"
                                        class="action-button" style="border-color:#fca5a5;color:#dc2626;background:#fef2f2;">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        <span>هناك مشكلة</span>
                                    </button>
                                @endif

                                {{-- منطق حجب رقم الهاتف الحرج (2.3) --}}
                                @if ($item->canRevealContactPhone() && $provPhone)
                                    <button type="button"
                                        onclick="openContactModal('{{ addslashes($provName) }}', '{{ $provPhone }}')"
                                        class="action-button">
                                        <i class="fa-solid fa-phone"></i>
                                        <span>تواصل مع المتطوع</span>
                                    </button>
                                @else
                                    <span class="phone-masked-notice">
                                        <i class="fa-solid fa-lock"></i>
                                        <span>سيظهر رقم التواصل بعد تأكيد الإسناد</span>
                                    </span>
                                @endif
                            </div>

                            {{-- منطق زر الإلغاء الحرج (2.2): ملفوف شرطياً بـ canBeCancelledByElderly() بدون استثناء --}}
                            @if ($item->canBeCancelledByElderly())
                                <button type="button"
                                    onclick="openCancelModal({{ $item->id }}, '{{ $item->public_id }}', '{{ route('service-requests.cancel', $item) }}')"
                                    class="action-button cancel-request">
                                    <i class="fa-regular fa-circle-xmark"></i>
                                    <span>إلغاء الطلب</span>
                                </button>
                            @endif
                        </div>
                    </article>

                @endif

            @empty
                <div class="requests-empty-state">
                    <div class="empty-icon"><i class="fa-solid fa-clipboard-list"></i></div>
                    <h3>لا توجد طلبات في هذا القسم حالياً</h3>
                    <p>يمكنك إنشاء طلب مساعدة جديد في أي وقت وسيتولى المتطوعون والجمعيات تقديم العون.</p>
                    <button type="button" onclick="openCreateRequestModal()" class="action-button schedule-and-repost" style="padding: 10px 24px; font-size: 13px; margin-top: 15px;">
                        <i class="fa-solid fa-plus"></i> <span>إنشاء طلب مساعدة جديد</span>
                    </button>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $requests->links() }}
            </div>
        </div>

    </div>
</x-app-layout>
