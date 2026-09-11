<x-admin-layout>

{{-- تبويبات سريعة --}}
<div style="display:flex; gap:10px; margin-bottom:20px; overflow-x:auto; padding-bottom:4px;">
    <a href="{{ route('admin.requests.index') }}"
        style="text-decoration:none; padding:8px 16px; border-radius:12px; font-size:12px; font-weight:700; white-space:nowrap; display:flex; align-items:center; gap:6px; {{ !request()->filled('status') ? 'background:#354e20;color:#fff;' : 'background:#fff;color:#4b5563;border:1px solid #e5e7eb;' }}">
        <span>كافة الطلبات</span>
        <span style="background:{{ !request()->filled('status') ? 'rgba(255,255,255,0.25)' : '#f3f4f6' }}; padding:2px 7px; border-radius:8px; font-size:11px;">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.requests.index', ['status' => 'active']) }}"
        style="text-decoration:none; padding:8px 16px; border-radius:12px; font-size:12px; font-weight:700; white-space:nowrap; display:flex; align-items:center; gap:6px; {{ request('status') === 'active' ? 'background:#354e20;color:#fff;' : 'background:#fff;color:#4b5563;border:1px solid #e5e7eb;' }}">
        <i class="fa-solid fa-spinner" style="font-size:11px;"></i>
        <span>النشطة الآن</span>
        <span style="background:{{ request('status') === 'active' ? 'rgba(255,255,255,0.25)' : '#f3f4f6' }}; padding:2px 7px; border-radius:8px; font-size:11px;">{{ $counts['active'] }}</span>
    </a>
    <a href="{{ route('admin.requests.index', ['status' => 'needs_action']) }}"
        style="text-decoration:none; padding:8px 16px; border-radius:12px; font-size:12px; font-weight:700; white-space:nowrap; display:flex; align-items:center; gap:6px; {{ request('status') === 'needs_action' ? 'background:#ea580c;color:#fff;' : 'background:#fff;color:#4b5563;border:1px solid #e5e7eb;' }}">
        <i class="fa-solid fa-triangle-exclamation" style="font-size:11px;"></i>
        <span>بحاجة لإجراء</span>
        <span style="background:{{ request('status') === 'needs_action' ? 'rgba(255,255,255,0.25)' : '#fee2e2;color:#991b1b;' }}; padding:2px 7px; border-radius:8px; font-size:11px;">{{ $counts['needs_action'] }}</span>
    </a>
    <a href="{{ route('admin.requests.index', ['status' => 'under_review']) }}"
        style="text-decoration:none; padding:8px 16px; border-radius:12px; font-size:12px; font-weight:700; white-space:nowrap; display:flex; align-items:center; gap:6px; {{ request('status') === 'under_review' ? 'background:#ef4444;color:#fff;' : 'background:#fff;color:#4b5563;border:1px solid #e5e7eb;' }}">
        <i class="fa-solid fa-flag" style="font-size:11px;"></i>
        <span>تحت المراجعة (اعتراض)</span>
        <span style="background:{{ request('status') === 'under_review' ? 'rgba(255,255,255,0.25)' : '#f3f4f6' }}; padding:2px 7px; border-radius:8px; font-size:11px;">{{ $counts['under_review'] }}</span>
    </a>
    <a href="{{ route('admin.requests.index', ['status' => 'completed']) }}"
        style="text-decoration:none; padding:8px 16px; border-radius:12px; font-size:12px; font-weight:700; white-space:nowrap; display:flex; align-items:center; gap:6px; {{ request('status') === 'completed' ? 'background:#10b981;color:#fff;' : 'background:#fff;color:#4b5563;border:1px solid #e5e7eb;' }}">
        <i class="fa-solid fa-check" style="font-size:11px;"></i>
        <span>المكتملة</span>
        <span style="background:{{ request('status') === 'completed' ? 'rgba(255,255,255,0.25)' : '#f3f4f6' }}; padding:2px 7px; border-radius:8px; font-size:11px;">{{ $counts['completed'] }}</span>
    </a>
</div>

{{-- فلاتر متقدمة --}}
<div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.06); margin-bottom:20px; border:1px solid #e2dcd0;">
    <form method="GET" action="{{ route('admin.requests.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:1; min-width:220px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">بحث بالمعرف أو العنوان أو الأسماء</label>
            <input name="search" value="{{ request('search') }}" placeholder="ابحث برقم الطلب #REQ، أو اسم المستفيد / المتطوع..."
                style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit; box-sizing:border-box;">
        </div>

        <div style="min-width:200px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">الحالة المحددة (11 حالة)</label>
            <select name="status" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit;">
                <option value="">كافة الحالات</option>
                @php
                    $statusLabels = [
                        'pending_acceptance'   => 'في انتظار مقدم خدمة',
                        'accepted'             => 'تم القبول مبدئياً',
                        'assigned'             => 'مُسند رسمياً للمتطوع',
                        'in_progress'          => 'قيد التنفيذ ميدانياً',
                        'pending_confirmation' => 'بانتظار تأكيد المستفيد',
                        'completed'            => 'مكتمل بنجاح',
                        'under_review'         => 'تحت المراجعة (شكوى/اعتراض)',
                        'provider_apologized'  => 'اعتذر مقدم الخدمة',
                        'provider_delayed'     => 'متأخر عن الموعد',
                        'no_provider_found'    => 'لم يتم العثور على متطوع',
                        'cancelled'            => 'ملغي',
                    ];
                @endphp
                @foreach ($statuses as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                        {{ $statusLabels[$s] ?? $s }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" style="background:#354e20; color:#fff; border:none; border-radius:10px; padding:10px 22px; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; display:flex; align-items:center; gap:6px;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>تصفية</span>
        </button>

        @if (request()->anyFilled(['search', 'status']))
        <a href="{{ route('admin.requests.index') }}" style="background:#f3f4f6; color:#374151; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:6px;">
            <i class="fa-solid fa-xmark"></i>
            <span>إعادة ضبط</span>
        </a>
        @endif
    </form>
</div>

{{-- جدول الطلبات --}}
<div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
    <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h3 style="margin:0; font-size:15px; font-weight:900; color:#1a1f36;">سجل طلبات الخدمة</h3>
            <p style="margin:4px 0 0; font-size:11px; color:#9ca3af;">إجمالي النتائج الحالية: {{ $requests->total() }} طلب</p>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:#fcfbf9; border-bottom:1px solid #e2dcd0;">
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">معرف الطلب</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">عنوان ونوع الخدمة</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">المستفيد</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">المتطوع المسند</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الموعد</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الحالة</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $req)
                @php
                    $statusStyles = [
                        'pending_acceptance'   => ['bg'=>'#fef3c7','color'=>'#92400e','label'=>'في الانتظار'],
                        'accepted'             => ['bg'=>'#ede9fe','color'=>'#5b21b6','label'=>'مقبول'],
                        'assigned'             => ['bg'=>'#e0f2fe','color'=>'#0369a1','label'=>'مُسند'],
                        'in_progress'          => ['bg'=>'#dbeafe','color'=>'#1e40af','label'=>'قيد التنفيذ'],
                        'pending_confirmation' => ['bg'=>'#ecfdf5','color'=>'#065f46','label'=>'بانتظار التأكيد'],
                        'completed'            => ['bg'=>'#d1fae5','color'=>'#065f46','label'=>'مكتمل'],
                        'under_review'         => ['bg'=>'#fee2e2','color'=>'#991b1b','label'=>'تحت المراجعة'],
                        'provider_apologized'  => ['bg'=>'#fce7f3','color'=>'#9d174d','label'=>'اعتذر المتطوع'],
                        'provider_delayed'     => ['bg'=>'#ffedd5','color'=>'#c2410c','label'=>'تأخير المتطوع'],
                        'no_provider_found'    => ['bg'=>'#f3f4f6','color'=>'#4b5563','label'=>'لم يُوجد متطوع'],
                        'cancelled'            => ['bg'=>'#f3f4f6','color'=>'#6b7280','label'=>'ملغي'],
                    ];
                    $st = $statusStyles[$req->status] ?? ['bg'=>'#f3f4f6','color'=>'#6b7280','label'=>$req->status];
                @endphp
                <tr style="border-top:1px solid #f3f4f6; transition:background 0.15s;" onmouseover="this.style.background='#faf9f5'" onmouseout="this.style.background='transparent'">
                    <td style="padding:14px 20px; font-weight:800; color:#354e20; white-space:nowrap; direction:ltr; text-align:right;">
                        {{ $req->public_id ?? '#'.$req->id }}
                    </td>
                    <td style="padding:14px 20px;">
                        <div style="font-weight:800; color:#1a1f36;">{{ Str::limit($req->title, 40) }}</div>
                        <div style="font-size:11px; color:#6b7280;">{{ $req->service_type }}</div>
                    </td>
                    <td style="padding:14px 20px;">
                        <div style="font-weight:700; color:#374151;">{{ $req->elderProfile?->user?->name ?? '—' }}</div>
                        <div style="font-size:11px; color:#9ca3af;">{{ $req->location }}</div>
                    </td>
                    <td style="padding:14px 20px;">
                        @if ($req->serviceProviderProfile?->user)
                            <div style="font-weight:700; color:#374151;">{{ $req->serviceProviderProfile->user->name }}</div>
                            <div style="font-size:11px; color:#f59e0b;">⭐ {{ number_format($req->serviceProviderProfile->average_rating ?? 0, 1) }}</div>
                        @else
                            <span style="color:#9ca3af; font-size:11px;">لم يُسند بعد</span>
                        @endif
                    </td>
                    <td style="padding:14px 20px; font-size:12px; color:#6b7280; white-space:nowrap;">
                        {{ $req->scheduled_at ? $req->scheduled_at->format('Y/m/d H:i') : 'غير محدد' }}
                    </td>
                    <td style="padding:14px 20px; white-space:nowrap;">
                        <span style="background:{{ $st['bg'] }}; color:{{ $st['color'] }}; padding:3px 10px; border-radius:8px; font-size:11px; font-weight:800;">
                            {{ $st['label'] }}
                        </span>
                    </td>
                    <td style="padding:14px 20px; white-space:nowrap;">
                        <a href="{{ route('admin.requests.show', $req) }}"
                            style="background:#e6edd9; color:#354e20; padding:6px 14px; border-radius:10px; font-size:11.5px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:5px;">
                            <i class="fa-solid fa-timeline"></i>
                            <span>التفاصيل والخط الزمني</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:48px 20px; text-align:center; color:#9ca3af;">
                        <i class="fa-regular fa-folder-open" style="font-size:36px; margin-bottom:8px; display:block;"></i>
                        لا توجد طلبات تطابق معايير البحث
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($requests->hasPages())
    <div style="padding:16px 20px; border-top:1px solid #f3f4f6;">
        {{ $requests->links() }}
    </div>
    @endif
</div>

</x-admin-layout>
