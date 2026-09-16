<x-app-layout>
    <style>
        .notif-page{width:min(100%,960px);margin-inline:auto}

        /* ── Header Banner ── */
        .notif-hero{
            display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;
            background:linear-gradient(135deg,#2c4318 0%,#3d5a26 55%,#4a6830 100%);
            border-radius:20px;padding:24px 28px;color:#fff;
            box-shadow:0 6px 24px rgba(44,67,24,.18);
            position:relative;overflow:hidden;
        }
        .notif-hero::after{
            content:'';position:absolute;bottom:-30px;left:-30px;width:120px;height:120px;
            border-radius:50%;background:rgba(122,157,80,.2);pointer-events:none;
        }
        .notif-hero-text{position:relative;z-index:1}
        .notif-hero-label{font-size:11px;font-weight:700;color:rgba(223,230,213,.75);letter-spacing:.2px}
        .notif-hero h1{margin:6px 0 4px;font-size:22px;font-weight:900;display:flex;align-items:center;gap:8px}
        .notif-hero h1 i{font-size:20px;color:#c8ddb0}
        .notif-hero p{font-size:12px;color:rgba(223,230,213,.8);font-weight:600;margin:0}
        .notif-mark-all{
            display:inline-flex;align-items:center;gap:6px;
            background:#fff;color:#2c4318;
            border:none;border-radius:12px;padding:9px 16px;
            font-size:11px;font-weight:800;cursor:pointer;
            transition:all .25s ease;box-shadow:0 2px 8px rgba(0,0,0,.08);
            position:relative;z-index:1;
        }
        .notif-mark-all:hover{background:#eef4e6;transform:translateY(-1px);box-shadow:0 4px 14px rgba(0,0,0,.1)}
        .notif-mark-all i{font-size:12px}

        /* ── Tabs ── */
        .notif-tabs{
            display:flex;gap:6px;margin:20px 0 18px;padding:4px;
            background:#fff;border-radius:14px;border:1px solid #e8e4dc;
            box-shadow:0 1px 4px rgba(44,67,24,.04);
            overflow-x:auto;
        }
        .notif-tab{
            display:inline-flex;align-items:center;gap:6px;
            padding:9px 16px;border-radius:10px;
            font-size:12px;font-weight:700;color:#718096;
            text-decoration:none;transition:all .2s ease;
            white-space:nowrap;border:1px solid transparent;
        }
        .notif-tab:hover{color:#2c4318;background:rgba(122,157,80,.06)}
        .notif-tab.active{
            background:#2c4318;color:#fff;
            box-shadow:0 2px 8px rgba(44,67,24,.2);
            border-color:transparent;
        }
        .notif-tab-count{
            font-size:10px;font-weight:800;
            padding:2px 7px;border-radius:8px;
            background:rgba(0,0,0,.06);color:inherit;
        }
        .notif-tab.active .notif-tab-count{background:rgba(255,255,255,.2);color:#fff}
        .notif-tab-count.alert{background:#ef4444;color:#fff}

        /* ── Status alerts ── */
        .notif-status-alert{
            display:flex;align-items:center;gap:10px;
            padding:12px 16px;border-radius:14px;margin-bottom:16px;
            font-size:12px;font-weight:700;
            border:1px solid #bbf7d0;background:#f0fdf4;color:#166534;
        }
        .notif-status-alert i{font-size:14px;color:#22c55e}

        /* ── Notification Cards ── */
        .notif-list{display:flex;flex-direction:column;gap:12px}

        .notif-card{
            background:#fff;
            border:1px solid #e8e4dc;
            border-radius:16px;
            padding:18px 20px;
            transition:all .25s ease;
            position:relative;
            overflow:hidden;
        }
        .notif-card::before{
            content:'';position:absolute;top:0;right:0;bottom:0;width:4px;
            border-radius:0 4px 4px 0;
        }
        .notif-card:hover{
            border-color:#cdd7c0;
            box-shadow:0 4px 16px rgba(44,67,24,.07);
            transform:translateY(-1px);
        }
        .notif-card.unread{
            background:linear-gradient(135deg,#fefdfb 0%,#fcfaf5 100%);
            border-color:#e5dfd3;
        }
        .notif-card.type-red::before{background:linear-gradient(180deg,#f87171,#ef4444)}
        .notif-card.type-orange::before{background:linear-gradient(180deg,#fbbf24,#f59e0b)}
        .notif-card.type-green::before{background:linear-gradient(180deg,#4ade80,#22c55e)}
        .notif-card.type-default::before{background:linear-gradient(180deg,#94a3b8,#64748b)}

        .notif-card-top{display:flex;align-items:flex-start;justify-content:space-between;gap:14px}
        .notif-card-body{display:flex;align-items:flex-start;gap:14px;flex:1;min-width:0}

        .notif-icon{
            width:42px;height:42px;flex:none;
            display:grid;place-items:center;
            border-radius:13px;font-size:18px;
        }
        .notif-icon.red{background:#fef2f2;color:#dc2626}
        .notif-icon.orange{background:#fffbeb;color:#d97706}
        .notif-icon.green{background:#f0fdf4;color:#16a34a}
        .notif-icon.default{background:#f1f5f9;color:#475569}

        .notif-title-row{display:flex;align-items:center;gap:8px;flex-wrap:wrap}
        .notif-title{font-size:14px;font-weight:800;color:#1e293b;margin:0}
        .notif-new-badge{
            font-size:9px;font-weight:800;
            background:linear-gradient(135deg,#fef3c7,#fde68a);
            color:#92400e;padding:2px 8px;border-radius:6px;
            letter-spacing:.3px;
        }
        .notif-message{
            margin:6px 0 0;font-size:13px;color:#4b5563;
            line-height:1.7;font-weight:500;
        }
        .notif-time{
            font-size:11px;font-weight:700;color:#94a3b8;
            white-space:nowrap;flex:none;padding-top:2px;
        }

        /* Listen button */
        .notif-listen-btn{
            display:inline-flex;align-items:center;gap:6px;
            margin-top:10px;padding:7px 14px;
            border-radius:10px;border:1px solid #dbeafe;
            background:#eff6ff;color:#1e40af;
            font-size:11px;font-weight:700;
            cursor:pointer;transition:all .2s ease;
        }
        .notif-listen-btn:hover{background:#dbeafe;border-color:#bfdbfe}
        .notif-listen-btn i{font-size:12px}

        /* Footer actions */
        .notif-card-footer{
            display:flex;align-items:center;justify-content:space-between;
            gap:12px;flex-wrap:wrap;
            margin-top:14px;padding-top:12px;
            border-top:1px solid #f1f0ec;
        }
        .notif-read-btn{
            display:inline-flex;align-items:center;gap:5px;
            font-size:11px;font-weight:700;color:#2c4318;
            background:none;border:none;cursor:pointer;
            padding:5px 10px;border-radius:8px;
            transition:all .2s;
        }
        .notif-read-btn:hover{background:#eef4e6;color:#1a2e0e}
        .notif-read-btn i{font-size:11px}
        .notif-read-done{
            display:inline-flex;align-items:center;gap:4px;
            font-size:11px;font-weight:700;color:#16a34a;
        }
        .notif-read-done i{font-size:10px}
        .notif-goto-link{
            display:inline-flex;align-items:center;gap:5px;
            font-size:11px;font-weight:700;color:#64748b;
            text-decoration:none;padding:5px 10px;border-radius:8px;
            transition:all .2s;
        }
        .notif-goto-link:hover{color:#2c4318;background:#f0f4ea}
        .notif-goto-link i{font-size:10px}

        /* ── Empty State ── */
        .notif-empty{
            text-align:center;padding:48px 24px;
            background:#fff;border:2px dashed #ddd8cd;
            border-radius:20px;
        }
        .notif-empty-icon{
            width:56px;height:56px;margin:0 auto 14px;
            display:grid;place-items:center;
            border-radius:16px;background:#f4f0e8;
            font-size:24px;color:#94a3b8;
        }
        .notif-empty h3{font-size:15px;font-weight:800;color:#334155;margin:0 0 6px}
        .notif-empty p{font-size:12px;color:#64748b;max-width:360px;margin:0 auto;line-height:1.7}

        @media(max-width:640px){
            .notif-hero{padding:18px 20px;border-radius:16px}
            .notif-hero h1{font-size:18px}
            .notif-tabs{gap:4px;padding:3px}
            .notif-tab{padding:7px 12px;font-size:11px}
            .notif-card{padding:14px 16px;border-radius:14px}
            .notif-icon{width:36px;height:36px;border-radius:10px;font-size:15px}
            .notif-card-top{flex-direction:column;gap:8px}
            .notif-time{align-self:flex-start}
        }
    </style>

    <div class="notif-page">

        {{-- ═══════ Header Banner ═══════ --}}
        <div class="notif-hero">
            <div class="notif-hero-text">
                <span class="notif-hero-label">لوحة التحكم</span>
                <h1><i class="fa-regular fa-bell"></i> الإشعارات</h1>
                <p>تابع تنبيهات ومستجدات طلباتك وحسابك أولاً بأول</p>
            </div>
            @if (($counts['unread'] ?? 0) > 0)
                <form method="POST" action="{{ route('notifications.mark-all-read') }}">
                    @csrf
                    <button type="submit" class="notif-mark-all">
                        <i class="fa-solid fa-check-double"></i>
                        <span>تحديد الكل كمقروء</span>
                    </button>
                </form>
            @endif
        </div>

        {{-- ═══════ Tabs ═══════ --}}
        <nav class="notif-tabs" aria-label="تبويبات الإشعارات">
            <a href="{{ route('notifications.index', ['tab' => 'all']) }}"
                class="notif-tab {{ ($tab ?? 'all') === 'all' ? 'active' : '' }}">
                <span>الكل</span>
                <span class="notif-tab-count">{{ $counts['all'] ?? 0 }}</span>
            </a>
            <a href="{{ route('notifications.index', ['tab' => 'unread']) }}"
                class="notif-tab {{ ($tab ?? 'all') === 'unread' ? 'active' : '' }}">
                <span>غير مقروءة</span>
                <span class="notif-tab-count {{ ($counts['unread'] ?? 0) > 0 ? 'alert' : '' }}">{{ $counts['unread'] ?? 0 }}</span>
            </a>
            <a href="{{ route('notifications.index', ['tab' => 'requests']) }}"
                class="notif-tab {{ ($tab ?? 'all') === 'requests' ? 'active' : '' }}">
                <span>الطلبات</span>
                <span class="notif-tab-count">{{ $counts['requests'] ?? 0 }}</span>
            </a>
        </nav>

        {{-- ═══════ Status Alerts ═══════ --}}
        @if (session('status') === 'notification-read')
            <div class="notif-status-alert">
                <i class="fa-solid fa-circle-check"></i>
                <span>تم تعليم الإشعار كمقروء بنجاح.</span>
            </div>
        @elseif (session('status') === 'all-notifications-read')
            <div class="notif-status-alert">
                <i class="fa-solid fa-circle-check"></i>
                <span>تم تعليم جميع الإشعارات كمقروءة بنجاح.</span>
            </div>
        @endif

        {{-- ═══════ Notification Cards ═══════ --}}
        <div class="notif-list">
            @forelse ($notifications as $notification)
                @php
                    $type = $notification->type;
                    $isRed = str_contains($type, 'problem') || str_contains($type, 'alert') || str_contains($type, 'cancelled') || str_contains($type, 'no_provider');
                    $isOrange = str_contains($type, 'delay') || str_contains($type, 'apolog') || str_contains($type, 'warning');
                    $isGreen = str_contains($type, 'upgrade') || str_contains($type, 'accept') || str_contains($type, 'completed') || str_contains($type, 'status');

                    $typeClass = $isRed ? 'type-red' : ($isOrange ? 'type-orange' : ($isGreen ? 'type-green' : 'type-default'));
                    $iconClass = $isRed ? 'red' : ($isOrange ? 'orange' : ($isGreen ? 'green' : 'default'));

                    $title = match(true) {
                        str_contains($type, 'apolog') => 'اعتذار عن الخدمة',
                        str_contains($type, 'delay') => 'تنبيه تأخر في التنفيذ',
                        str_contains($type, 'problem') => 'إحالة بلاغ للمراجعة الإدارية',
                        str_contains($type, 'no_provider') => 'لم يتوفر مقدم خدمة',
                        str_contains($type, 'upgrade') => 'ترقية المستوى التقديري (Tier)',
                        str_contains($type, 'downgrade') => 'تحديث المستوى التقديري (Tier)',
                        str_contains($type, 'alert') => 'تنبيه إداري مهم',
                        default => 'إشعار من النظام',
                    };

                    $iconFA = match(true) {
                        $isRed => 'fa-solid fa-circle-exclamation',
                        $isOrange => 'fa-solid fa-clock-rotate-left',
                        $isGreen => 'fa-solid fa-circle-check',
                        default => 'fa-solid fa-bell',
                    };
                @endphp

                <article class="notif-card {{ $typeClass }} {{ !$notification->is_read ? 'unread' : '' }}">
                    <div class="notif-card-top">
                        <div class="notif-card-body">
                            <div class="notif-icon {{ $iconClass }}">
                                <i class="{{ $iconFA }}"></i>
                            </div>
                            <div style="flex:1;min-width:0">
                                <div class="notif-title-row">
                                    <h2 class="notif-title">{{ $title }}</h2>
                                    @if (!$notification->is_read)
                                        <span class="notif-new-badge">جديد</span>
                                    @endif
                                </div>
                                <p class="notif-message">{{ $notification->message }}</p>
                                <button type="button"
                                    data-tts-text="{{ $title }}. {{ $notification->message }}"
                                    class="notif-listen-btn"
                                    aria-label="قراءة الإشعار بصوت مرتفع">
                                    <i class="fa-solid fa-volume-high"></i>
                                    <span>استمع للإشعار</span>
                                </button>
                            </div>
                        </div>
                        <time datetime="{{ $notification->created_at->toIso8601String() }}" class="notif-time">
                            {{ $notification->created_at->diffForHumans() }}
                        </time>
                    </div>

                    <div class="notif-card-footer">
                        <div>
                            @if (!$notification->is_read)
                                <form method="POST" action="{{ route('notifications.read', $notification) }}" style="display:inline">
                                    @csrf
                                    @method('patch')
                                    <button type="submit" class="notif-read-btn">
                                        <i class="fa-solid fa-check"></i>
                                        <span>تحديد كمقروء</span>
                                    </button>
                                </form>
                            @else
                                <span class="notif-read-done">
                                    <i class="fa-solid fa-check-double"></i>
                                    <span>مقروء</span>
                                </span>
                            @endif
                        </div>

                        {{-- روابط سريعة للطلبات --}}
                        @if (str_contains($type, 'provider') || str_contains($type, 'request') || str_contains($type, 'problem'))
                            <div>
                                @if (Auth::user()->isProvider())
                                    <a href="{{ route('provider.tasks') }}" class="notif-goto-link">
                                        <span>الانتقال لطلباتي</span>
                                        <i class="fa-solid fa-arrow-left"></i>
                                    </a>
                                @else
                                    <a href="{{ route('service-requests.index') }}" class="notif-goto-link">
                                        <span>الانتقال لطلباتي</span>
                                        <i class="fa-solid fa-arrow-left"></i>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <div class="notif-empty">
                    <div class="notif-empty-icon">
                        <i class="fa-regular fa-bell-slash"></i>
                    </div>
                    <h3>لا توجد إشعارات في هذا التبويب</h3>
                    <p>ستظهر هنا كافة التنبيهات المرتبطة بنشاطك على المنصة فور حدوثها.</p>
                </div>
            @endforelse
        </div>

        {{-- ترقيم الصفحات --}}
        @if ($notifications->hasPages())
            <div style="margin-top:24px">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
