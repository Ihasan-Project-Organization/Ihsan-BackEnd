<x-app-layout>
    @php($user = auth()->user())
    @php($elderProfile = $user->elderProfile)

    @if (request()->boolean('assistant'))
        <div class="mx-auto max-w-6xl">
            <x-elderly-assistant :profile-city="$elderProfile?->city" />
        </div>
    @else
        @php($activeCount = $user->serviceRequests()->active()->count())
        @php($needsActionCount = $user->serviceRequests()->needsAction()->count())
        @php($completedCount = $user->serviceRequests()->completed()->count())

        <style>
            .elder-dash{width:min(100%,1080px);margin-inline:auto;display:grid;gap:18px}

            /* ── Welcome Card ── */
            .elder-welcome{
                display:grid;grid-template-columns:minmax(0,1.3fr) minmax(300px,.7fr);gap:16px;
                background:#fff;border:1px solid #e4dfd6;border-radius:20px;
                padding:20px;box-shadow:0 4px 18px rgba(44,67,24,.06);
                position:relative;overflow:hidden;
            }
            .elder-welcome::after{
                content:'';position:absolute;top:-40px;left:-40px;width:140px;height:140px;
                border-radius:50%;background:rgba(122,157,80,.06);pointer-events:none;
            }

            .elder-welcome-copy{display:flex;min-width:0;flex-direction:column;justify-content:center;padding:10px 12px;position:relative;z-index:1}
            .elder-status{
                display:inline-flex;width:max-content;align-items:center;gap:6px;
                border-radius:20px;background:#edf6e8;padding:5px 11px;
                color:#3d6125;font-size:10px;font-weight:800;
            }
            .elder-status i{color:#4caf50;font-size:9px}
            .elder-welcome-copy h1{margin:12px 0 4px;color:#1e2e14;font-size:24px;font-weight:900;line-height:1.4}
            .elder-welcome-copy>p{margin:0;color:#6b7a62;font-size:12px;font-weight:600;line-height:1.6}
            .elder-meta{display:flex;flex-wrap:wrap;gap:14px;margin-top:14px;color:#8c9585;font-size:10px;font-weight:700}
            .elder-meta span{display:inline-flex;align-items:center;gap:5px}
            .elder-meta i{color:#6d8b52}

            /* ── Assistant Card ── */
            .elder-assistant{
                display:grid;grid-template-columns:46px minmax(0,1fr);align-items:center;gap:12px;
                border-radius:18px;
                background:linear-gradient(150deg,#1e3212 0%,#2c4318 45%,#3a5824 100%);
                padding:18px;color:#fff;
                box-shadow:0 6px 22px rgba(44,67,24,.18);
                position:relative;overflow:hidden;
            }
            .elder-assistant::before{
                content:'';position:absolute;top:-20px;right:-20px;width:80px;height:80px;
                border-radius:50%;background:rgba(122,157,80,.15);pointer-events:none;
            }
            .elder-assistant-icon{
                display:grid;width:46px;height:46px;place-items:center;
                border:1px solid rgba(255,255,255,.15);border-radius:14px;
                background:rgba(255,255,255,.08);font-size:18px;
            }
            .elder-assistant-copy{min-width:0}
            .elder-assistant-copy small{display:block;color:rgba(189,211,170,.8);font-size:9px;font-weight:800;letter-spacing:.15px}
            .elder-assistant-copy strong{display:block;margin-top:3px;font-size:14px;font-weight:900}
            .elder-assistant-copy p{margin-top:4px;color:rgba(217,230,207,.75);font-size:9px;font-weight:600;line-height:1.65}
            .elder-assistant>a{
                grid-column:1/-1;display:flex;align-items:center;justify-content:space-between;
                border-radius:11px;background:#fff;padding:10px 13px;
                color:#1e3212;font-size:11px;font-weight:900;
                text-decoration:none;transition:all .25s ease;
            }
            .elder-assistant>a:hover{background:#eef4e6;transform:translateY(-1px);box-shadow:0 2px 8px rgba(0,0,0,.06)}

            /* ── Summary Section ── */
            .elder-summary{
                background:#fff;border:1px solid #e4dfd6;border-radius:20px;
                padding:20px;box-shadow:0 4px 18px rgba(44,67,24,.05);
            }
            .elder-summary-header{
                display:flex;align-items:center;justify-content:space-between;gap:16px;
                margin-bottom:14px;
            }
            .elder-summary-header span{color:#6d8b52;font-size:9px;font-weight:800;letter-spacing:.2px}
            .elder-summary-header h2{margin-top:3px;color:#1e2e14;font-size:17px;font-weight:900}
            .elder-summary-header>a{
                display:inline-flex;align-items:center;gap:6px;
                border-radius:10px;background:#f1f5ed;padding:7px 11px;
                color:#3d6125;font-size:9px;font-weight:800;
                text-decoration:none;transition:all .2s;
            }
            .elder-summary-header>a:hover{background:#e2ebd6;transform:translateY(-1px)}

            .elder-summary-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
            .elder-summary-item{
                display:flex;min-width:0;align-items:center;gap:10px;
                border:1px solid #ebeee7;border-radius:14px;background:#fafbf9;
                padding:14px;text-decoration:none;
                transition:all .25s ease;
            }
            .elder-summary-item:hover{
                transform:translateY(-2px);border-color:#c4d3b6;
                background:#fff;box-shadow:0 6px 18px rgba(44,67,24,.07);
            }
            .elder-summary-icon{
                display:grid;width:40px;height:40px;flex:none;
                place-items:center;border-radius:12px;
                font-size:15px;
            }
            .elder-summary-icon.active-icon{background:#eaf1e4;color:#3d6125}
            .elder-summary-icon.action-icon{background:#fff2dc;color:#b66c05}
            .elder-summary-icon.done-icon{background:#e5f5eb;color:#16804a}
            .elder-summary-item.needs-attention{border-color:#efcf94;background:#fffaf1}

            .elder-summary-content{display:flex;min-width:0;flex:1;flex-direction:column}
            .elder-summary-content small{color:#7e8878;font-size:9px;font-weight:800}
            .elder-summary-content strong{color:#1e2e14;font-size:20px;font-weight:900;line-height:1.3}
            .elder-summary-content em{
                overflow:hidden;color:#9aa393;font-size:8px;font-style:normal;
                font-weight:700;text-overflow:ellipsis;white-space:nowrap;
            }
            .elder-summary-arrow{color:#bdc6b6;font-size:9px}

            @media(max-width:820px){
                .elder-welcome{grid-template-columns:1fr}
                .elder-assistant{grid-template-columns:40px minmax(0,1fr)}
                .elder-summary-grid{grid-template-columns:1fr}
            }
            @media(max-width:640px){
                .elder-dash{gap:12px}
                .elder-welcome,.elder-summary{border-radius:16px;padding:14px}
                .elder-welcome-copy{padding:8px}
                .elder-welcome-copy h1{font-size:19px}
                .elder-meta{gap:8px}
                .elder-summary-header{align-items:flex-start}
                .elder-summary-header>a{white-space:nowrap}
            }
        </style>

        <main class="elder-dash">
            <section class="elder-welcome">
                <div class="elder-welcome-copy">
                    <span class="elder-status"><i class="fa-solid fa-circle-check"></i> حساب معتمد</span>
                    <h1>أهلًا، {{ $user->name }}</h1>
                    <p>كل ما تحتاجه لإدارة طلباتك موجود هنا بشكل بسيط وواضح.</p>
                    <div class="elder-meta">
                        <span><i class="fa-solid fa-location-dot"></i> {{ $elderProfile?->city ?? 'المدينة غير محددة' }}</span>
                        <span><i class="fa-regular fa-calendar"></i> عضو منذ {{ $user->created_at->translatedFormat('M Y') }}</span>
                    </div>
                </div>

                <div class="elder-assistant">
                    <span class="elder-assistant-icon"><i class="fa-solid fa-robot"></i></span>
                    <div class="elder-assistant-copy">
                        <small>مساعد أنيس الذكي</small>
                        <strong>كيف نقدر نساعدك اليوم؟</strong>
                        <p>ابدأ محادثة سهلة واختر الخدمة خطوة بخطوة.</p>
                    </div>
                    <a href="{{ route('dashboard', ['assistant' => 1]) }}">
                        <span>فتح المساعد</span>
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>
            </section>

            <section class="elder-summary" aria-labelledby="summary-title">
                <header class="elder-summary-header">
                    <div>
                        <span>نظرة سريعة</span>
                        <h2 id="summary-title">حالة طلباتك</h2>
                    </div>
                    <a href="{{ route('service-requests.index') }}">عرض كل الطلبات <i class="fa-solid fa-arrow-left"></i></a>
                </header>

                <div class="elder-summary-grid">
                    <a href="{{ route('service-requests.index', ['tab' => 'active']) }}" class="elder-summary-item">
                        <span class="elder-summary-icon active-icon"><i class="fa-solid fa-clock-rotate-left"></i></span>
                        <span class="elder-summary-content">
                            <small>طلبات نشطة</small>
                            <strong>{{ $activeCount }}</strong>
                            <em>قيد المتابعة</em>
                        </span>
                        <i class="fa-solid fa-chevron-left elder-summary-arrow"></i>
                    </a>

                    <a href="{{ route('service-requests.index', ['tab' => 'needs_action']) }}" class="elder-summary-item {{ $needsActionCount > 0 ? 'needs-attention' : '' }}">
                        <span class="elder-summary-icon action-icon"><i class="fa-solid fa-circle-exclamation"></i></span>
                        <span class="elder-summary-content">
                            <small>بحاجة لإجراء</small>
                            <strong>{{ $needsActionCount }}</strong>
                            <em>{{ $needsActionCount > 0 ? 'بانتظار مراجعتك' : 'لا يوجد إجراء مطلوب' }}</em>
                        </span>
                        <i class="fa-solid fa-chevron-left elder-summary-arrow"></i>
                    </a>

                    <a href="{{ route('service-requests.index', ['tab' => 'completed']) }}" class="elder-summary-item">
                        <span class="elder-summary-icon done-icon"><i class="fa-solid fa-circle-check"></i></span>
                        <span class="elder-summary-content">
                            <small>طلبات مكتملة</small>
                            <strong>{{ $completedCount }}</strong>
                            <em>تم إنجازها بنجاح</em>
                        </span>
                        <i class="fa-solid fa-chevron-left elder-summary-arrow"></i>
                    </a>
                </div>
            </section>
        </main>
    @endif
</x-app-layout>