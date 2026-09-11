<x-admin-layout>

{{-- ========== شبكة بطاقات KPI السبع المطلوبة ========== --}}
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap:16px; margin-bottom:28px;">

    {{-- 1. كبار السن --}}
    <a href="{{ Route::has('admin.users.index') ? route('admin.users.index', ['role' => 'elder']) : '#' }}"
        style="text-decoration:none; display:block; background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #3b82f6; transition:transform 0.2s, box-shadow 0.2s;"
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)';"
        onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 6px rgba(0,0,0,0.05)';">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
            <span style="font-size:11.5px; font-weight:700; color:#6b7280;">كبار السن (المستفيدون)</span>
            <div style="width:36px;height:36px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;color:#3b82f6;font-size:15px;">
                <i class="fa-solid fa-person-cane"></i>
            </div>
        </div>
        <div style="font-size:28px; font-weight:900; color:#1a1f36;">{{ number_format($eldersCount) }}</div>
        <div style="margin-top:6px; font-size:11px; color:#3b82f6; font-weight:700; display:flex; align-items:center; gap:4px;">
            <span>عرض المستفيدين</span>
            <i class="fa-solid fa-arrow-left" style="font-size:9px;"></i>
        </div>
    </a>

    {{-- 2. مقدمو الخدمة --}}
    <a href="{{ Route::has('admin.users.index') ? route('admin.users.index', ['role' => 'provider']) : '#' }}"
        style="text-decoration:none; display:block; background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #10b981; transition:transform 0.2s, box-shadow 0.2s;"
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)';"
        onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 6px rgba(0,0,0,0.05)';">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
            <span style="font-size:11.5px; font-weight:700; color:#6b7280;">مقدمو الخدمة (المتطوعون)</span>
            <div style="width:36px;height:36px;border-radius:10px;background:#ecfdf5;display:flex;align-items:center;justify-content:center;color:#10b981;font-size:15px;">
                <i class="fa-solid fa-hand-holding-heart"></i>
            </div>
        </div>
        <div style="font-size:28px; font-weight:900; color:#1a1f36;">{{ number_format($providersCount) }}</div>
        <div style="margin-top:6px; font-size:11px; color:#10b981; font-weight:700; display:flex; align-items:center; gap:4px;">
            <span>عرض المتطوعين</span>
            <i class="fa-solid fa-arrow-left" style="font-size:9px;"></i>
        </div>
    </a>

    {{-- 3. بانتظار الاعتماد --}}
    <a href="{{ route('admin.approvals.index') }}"
        style="text-decoration:none; display:block; background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #f59e0b; transition:transform 0.2s, box-shadow 0.2s;"
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)';"
        onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 6px rgba(0,0,0,0.05)';">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
            <span style="font-size:11.5px; font-weight:700; color:#6b7280;">بانتظار الاعتماد</span>
            <div style="width:36px;height:36px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;color:#f59e0b;font-size:15px;">
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>
        <div style="font-size:28px; font-weight:900; color:#1a1f36;">{{ number_format($pendingAccountsCount) }}</div>
        <div style="margin-top:6px; font-size:11px; color:#d97706; font-weight:700; display:flex; align-items:center; gap:4px;">
            <span>مراجعة الطلبات</span>
            <i class="fa-solid fa-arrow-left" style="font-size:9px;"></i>
        </div>
    </a>

    {{-- 4. الطلبات النشطة --}}
    <a href="{{ Route::has('admin.requests.index') ? route('admin.requests.index', ['status' => 'active']) : '#' }}"
        style="text-decoration:none; display:block; background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border:1px solid #e2dcd0; border-top:4px solid #354e20; transition:transform 0.2s, box-shadow 0.2s;"
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)';"
        onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 6px rgba(0,0,0,0.05)';">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
            <span style="font-size:11.5px; font-weight:700; color:#6b7280;">الطلبات النشطة</span>
            <div style="width:36px;height:36px;border-radius:10px;background:#e6edd9;display:flex;align-items:center;justify-content:center;color:#354e20;font-size:15px;">
                <i class="fa-solid fa-spinner"></i>
            </div>
        </div>
        <div style="font-size:28px; font-weight:900; color:#1a1f36;">{{ number_format($activeRequestsCount) }}</div>
        <div style="margin-top:6px; font-size:11px; color:#354e20; font-weight:700; display:flex; align-items:center; gap:4px;">
            <span>مقبولة ومسندة وجارية</span>
            <i class="fa-solid fa-arrow-left" style="font-size:9px;"></i>
        </div>
    </a>

    {{-- 5. طلبات بحاجة لإجراء --}}
    <a href="{{ Route::has('admin.requests.index') ? route('admin.requests.index', ['status' => 'needs_action']) : '#' }}"
        style="text-decoration:none; display:block; background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #ea580c; transition:transform 0.2s, box-shadow 0.2s;"
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)';"
        onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 6px rgba(0,0,0,0.05)';">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
            <span style="font-size:11.5px; font-weight:700; color:#6b7280;">بحاجة لإجراء عاجل</span>
            <div style="width:36px;height:36px;border-radius:10px;background:#ffedd5;display:flex;align-items:center;justify-content:center;color:#ea580c;font-size:15px;">
                <i class="fa-solid fa-bell-exclamation"></i>
            </div>
        </div>
        <div style="font-size:28px; font-weight:900; color:{{ $needsActionRequestsCount > 0 ? '#ea580c' : '#1a1f36' }};">
            {{ number_format($needsActionRequestsCount) }}
        </div>
        <div style="margin-top:6px; font-size:11px; color:#ea580c; font-weight:700; display:flex; align-items:center; gap:4px;">
            <span>اعتذار / تأخير / لا يوجد مقدم</span>
            <i class="fa-solid fa-arrow-left" style="font-size:9px;"></i>
        </div>
    </a>

    {{-- 6. البلاغات المفتوحة --}}
    <a href="{{ Route::has('admin.complaints.index') ? route('admin.complaints.index', ['status' => 'open']) : (Route::has('admin.reports.index') ? route('admin.reports.index', ['status' => 'open']) : '#') }}"
        style="text-decoration:none; display:block; background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #ef4444; transition:transform 0.2s, box-shadow 0.2s;"
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)';"
        onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 6px rgba(0,0,0,0.05)';">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
            <span style="font-size:11.5px; font-weight:700; color:#6b7280;">البلاغات والشكاوى المفتوحة</span>
            <div style="width:36px;height:36px;border-radius:10px;background:#fee2e2;display:flex;align-items:center;justify-content:center;color:#ef4444;font-size:15px;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>
        <div style="font-size:28px; font-weight:900; color:{{ $openComplaintsCount > 0 ? '#ef4444' : '#1a1f36' }};">
            {{ number_format($openComplaintsCount) }}
        </div>
        <div style="margin-top:6px; font-size:11px; color:#ef4444; font-weight:700; display:flex; align-items:center; gap:4px;">
            <span>متابعة الشكاوى</span>
            <i class="fa-solid fa-arrow-left" style="font-size:9px;"></i>
        </div>
    </a>

    {{-- 7. تنبيهات الموثوقية --}}
    <a href="{{ Route::has('admin.complaints.index') ? route('admin.complaints.index', ['tab' => 'reliability']) : (Route::has('admin.reports.index') ? route('admin.reports.index', ['tab' => 'reliability']) : '#') }}"
        style="text-decoration:none; display:block; background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #8b5cf6; transition:transform 0.2s, box-shadow 0.2s;"
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 16px rgba(0,0,0,0.08)';"
        onmouseout="this.style.transform='none';this.style.boxShadow='0 1px 6px rgba(0,0,0,0.05)';">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
            <span style="font-size:11.5px; font-weight:700; color:#6b7280;">تنبيهات الموثوقية (30 يوماً)</span>
            <div style="width:36px;height:36px;border-radius:10px;background:#f5f3ff;display:flex;align-items:center;justify-content:center;color:#8b5cf6;font-size:15px;">
                <i class="fa-solid fa-shield-virus"></i>
            </div>
        </div>
        <div style="font-size:28px; font-weight:900; color:{{ $reliabilityAlertsCount > 0 ? '#8b5cf6' : '#1a1f36' }};">
            {{ number_format($reliabilityAlertsCount) }}
        </div>
        <div style="margin-top:6px; font-size:11px; color:#8b5cf6; font-weight:700; display:flex; align-items:center; gap:4px;">
            <span>3+ حوادث عدم موثوقية</span>
            <i class="fa-solid fa-arrow-left" style="font-size:9px;"></i>
        </div>
    </a>

</div>

{{-- ========== الصف الثاني: طلبات الاعتماد العاجلة + آخر الأنشطة ========== --}}
<div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:24px;">

    {{-- طلبات الاعتماد بانتظار المراجعة --}}
    <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-user-clock" style="color:#f59e0b;"></i>
                <h3 style="font-size:13px; font-weight:800; color:#1a1f36; margin:0;">طلبات تسجيل بانتظار الاعتماد</h3>
            </div>
            <a href="{{ route('admin.approvals.index') }}" style="font-size:11px; color:#354e20; font-weight:700; text-decoration:none;">عرض الكل ({{ $pendingAccountsCount }})</a>
        </div>
        <div style="padding:8px 0;">
            @forelse ($recentPendingUsers as $pUser)
            <div style="padding:12px 20px; border-bottom:1px solid #f9fafb; display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:36px;height:36px;border-radius:50%;background:#e6edd9;display:flex;align-items:center;justify-content:center;font-weight:800;color:#354e20;font-size:13px;">
                        {{ mb_substr($pUser->name, 0, 1) }}
                    </div>
                    <div>
                        <div style="font-size:13px; font-weight:700; color:#1a1f36;">{{ $pUser->name }}</div>
                        <div style="font-size:11px; color:#6b7280;">
                            {{ $pUser->isProvider() ? 'مقدم خدمة متطوع' : 'كبير سن (مستفيد)' }}
                            · {{ $pUser->email }}
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.approvals.show', $pUser) }}"
                    style="background:#e6edd9;color:#354e20;padding:6px 14px;border-radius:10px;font-size:11px;font-weight:700;text-decoration:none;">
                    معاينة واعتماد ←
                </a>
            </div>
            @empty
            <div style="padding:32px 20px; text-align:center; color:#9ca3af; font-size:12px;">
                <i class="fa-regular fa-circle-check" style="font-size:24px; color:#83a55b; margin-bottom:6px; display:block;"></i>
                لا توجد طلبات تسجيل معلقة حالياً
            </div>
            @endforelse
        </div>
    </div>

    {{-- آخر البلاغات والشكاوى --}}
    <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden;">
        <div style="padding:16px 20px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;"></i>
                <h3 style="font-size:13px; font-weight:800; color:#1a1f36; margin:0;">آخر الشكاوى والبلاغات</h3>
            </div>
            <a href="{{ Route::has('admin.complaints.index') ? route('admin.complaints.index') : (Route::has('admin.reports.index') ? route('admin.reports.index') : '#') }}"
                style="font-size:11px; color:#354e20; font-weight:700; text-decoration:none;">عرض الكل ({{ $openComplaintsCount }})</a>
        </div>
        <div style="padding:8px 0;">
            @forelse ($recentComplaints as $c)
            <div style="padding:12px 20px; border-bottom:1px solid #f9fafb; display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <div style="font-size:13px; font-weight:700; color:#374151;">{{ $c->reporter?->name ?? 'مجهول' }}</div>
                    <div style="font-size:11px; color:#9ca3af; margin-top:2px;">{{ Str::limit($c->description, 45) }}</div>
                </div>
                <span style="background:{{ $c->status === 'open' ? '#fee2e2' : '#d1fae5' }};color:{{ $c->status === 'open' ? '#991b1b' : '#065f46' }};padding:3px 10px;border-radius:8px;font-size:10px;font-weight:700;">
                    {{ $c->status === 'open' ? 'مفتوحة' : 'مغلقة' }}
                </span>
            </div>
            @empty
            <div style="padding:32px 20px; text-align:center; color:#9ca3af; font-size:12px;">
                <i class="fa-solid fa-shield-halved" style="font-size:24px; color:#83a55b; margin-bottom:6px; display:block;"></i>
                لا توجد شكاوى مسجلة
            </div>
            @endforelse
        </div>
    </div>

</div>

</x-admin-layout>
