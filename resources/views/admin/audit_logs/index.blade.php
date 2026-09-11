<x-admin-layout>

{{-- الإحصائيات السريعة لسجل التدقيق --}}
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px;">
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border:1px solid #e2dcd0; border-top:4px solid #354e20;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">إجمالي العمليات المسجلة</span>
        <div style="font-size:26px; font-weight:900; color:#1a1f36;">{{ number_format($stats['total']) }}</div>
    </div>
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #3b82f6;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">عمليات اليوم</span>
        <div style="font-size:26px; font-weight:900; color:#1d4ed8;">{{ number_format($stats['today']) }}</div>
    </div>
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #10b981;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">اعتمادات الحسابات</span>
        <div style="font-size:26px; font-weight:900; color:#065f46;">{{ number_format($stats['approvals']) }}</div>
    </div>
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #ef4444;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">قرارات الإيقاف والتعليق</span>
        <div style="font-size:26px; font-weight:900; color:#991b1b;">{{ number_format($stats['suspensions']) }}</div>
    </div>
</div>

{{-- شريط العنوان والوصف --}}
<div style="margin-bottom:20px;">
    <h2 style="margin:0; font-size:20px; font-weight:900; color:#1a1f36;">سجل النظام والتدقيق الإداري (Audit Log)</h2>
    <p style="margin:4px 0 0; font-size:12px; color:#6b7280;">سجل فوري ومؤرشف لكافة العمليات الإدارية والقرارات المتخذة عبر منصة إحسان لضمان الشفافية والمساءلة.</p>
</div>

{{-- فلاتر البحث المتقدمة --}}
<div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.06); margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.audit-log.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        {{-- بحث بالكلمة المفتاحية --}}
        <div style="flex:1; min-width:200px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">بحث في الأسباب والتفاصيل</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن سبب، اسم مستخدم، أو مدير..."
                style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit; box-sizing:border-box;">
        </div>

        {{-- نوع الإجراء --}}
        <div style="min-width:170px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">نوع الإجراء</label>
            <select name="action" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit;">
                <option value="">كافة الإجراءات</option>
                @foreach($actionsList as $actKey => $actTitle)
                    <option value="{{ $actKey }}" {{ request('action') === $actKey ? 'selected' : '' }}>
                        {{ $actTitle }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- المدير المنفذ --}}
        <div style="min-width:170px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">المدير المنفذ</label>
            <select name="admin_id" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit;">
                <option value="">كافة المديرين</option>
                @foreach($adminsList as $adminOption)
                    <option value="{{ $adminOption->id }}" {{ (string) request('admin_id') === (string) $adminOption->id ? 'selected' : '' }}>
                        {{ $adminOption->name }} ({{ $adminOption->admin?->admin_level === 'super_admin' ? 'Super Admin' : 'Admin' }})
                    </option>
                @endforeach
            </select>
        </div>

        {{-- التاريخ من --}}
        <div style="min-width:130px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">من تاريخ</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 10px; font-size:12px; font-family:inherit; box-sizing:border-box;">
        </div>

        {{-- التاريخ إلى --}}
        <div style="min-width:130px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">إلى تاريخ</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 10px; font-size:12px; font-family:inherit; box-sizing:border-box;">
        </div>

        <button type="submit" style="background:#354e20; color:#fff; border:none; border-radius:10px; padding:10px 22px; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; display:flex; align-items:center; gap:6px;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>تصفية</span>
        </button>

        @if (request()->anyFilled(['search', 'action', 'admin_id', 'date_from', 'date_to']))
            <a href="{{ route('admin.audit-log.index') }}" style="background:#f3f4f6; color:#374151; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-xmark"></i>
                <span>إلغاء التصفية</span>
            </a>
        @endif
    </form>
</div>

{{-- جدول السجل والتدقيق --}}
<div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:right; font-size:13px;">
            <thead>
                <tr style="background:#fcfbf9; border-bottom:1px solid #e2dcd0; color:#4b5563;">
                    <th style="padding:14px 18px; font-weight:800; width:170px;">التوقيت والتاريخ</th>
                    <th style="padding:14px 18px; font-weight:800;">المدير المنفذ</th>
                    <th style="padding:14px 18px; font-weight:800;">نوع الإجراء</th>
                    <th style="padding:14px 18px; font-weight:800;">الجهة / الكيان المتأثر</th>
                    <th style="padding:14px 18px; font-weight:800;">السبب / الملاحظات والتفاصيل</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    @php
                        $badge = $log->badge_details;
                    @endphp
                    <tr style="border-bottom:1px solid #f3f4f6; transition:background 0.15s;" onmouseover="this.style.background='#faf9f5'" onmouseout="this.style.background='transparent'">
                        {{-- التوقيت --}}
                        <td style="padding:14px 18px; vertical-align:top;">
                            <div style="font-weight:700; color:#1a1f36; font-size:12px;">
                                {{ $log->created_at ? $log->created_at->translatedFormat('Y/m/d') : '—' }}
                            </div>
                            <div style="font-size:11px; color:#6b7280; margin-top:2px;">
                                {{ $log->created_at ? $log->created_at->translatedFormat('h:i:s A') : '' }}
                                <span style="font-size:10px; color:#9ca3af;">({{ $log->created_at ? $log->created_at->diffForHumans() : '' }})</span>
                            </div>
                        </td>

                        {{-- المدير --}}
                        <td style="padding:14px 18px; vertical-align:top;">
                            @if ($log->admin)
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <div style="width:32px; height:32px; border-radius:8px; background:#e6edd9; color:#354e20; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:12px;">
                                        <i class="fa-solid {{ $log->admin->admin?->admin_level === 'super_admin' ? 'fa-crown' : 'fa-user-shield' }}"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight:800; color:#1a1f36; font-size:12px;">{{ $log->admin->name }}</div>
                                        <div style="font-size:10px; color:#6b7280;">{{ $log->admin->email }}</div>
                                    </div>
                                </div>
                            @else
                                <span style="color:#9ca3af; font-size:12px;">مدير محذوف #{{ $log->admin_id }}</span>
                            @endif
                        </td>

                        {{-- نوع الإجراء --}}
                        <td style="padding:14px 18px; vertical-align:top;">
                            <span style="background:{{ $badge['bg'] }}; color:{{ $badge['color'] }}; border:1px solid {{ $badge['border'] }}; border-radius:20px; padding:4px 12px; font-size:11px; font-weight:800; display:inline-flex; align-items:center; gap:5px; white-space:nowrap;">
                                <i class="fa-solid {{ $badge['icon'] }}"></i>
                                {{ $log->action_label }}
                            </span>
                        </td>

                        {{-- الكيان المتأثر --}}
                        <td style="padding:14px 18px; vertical-align:top;">
                            <div style="font-weight:700; color:#374151; font-size:12px;">
                                {{ $log->target_description }}
                            </div>
                            @if ($log->target_type)
                                <span style="font-size:10px; color:#9ca3af; background:#f3f4f6; border-radius:6px; padding:2px 6px; display:inline-block; margin-top:3px;">
                                    {{ $log->target_type }}
                                </span>
                            @endif
                        </td>

                        {{-- السبب والملاحظات --}}
                        <td style="padding:14px 18px; vertical-align:top;">
                            @if ($log->reason)
                                <div style="color:#1f2937; font-size:12px; line-height:1.5; background:#f9fafb; border-right:3px solid #d1d5db; padding:6px 10px; border-radius:0 8px 8px 0;">
                                    {{ $log->reason }}
                                </div>
                            @else
                                <span style="color:#9ca3af; font-size:12px;">—</span>
                            @endif

                            {{-- تفاصيل إضافية من metadata إن وجدت --}}
                            @if (!empty($log->metadata) && is_array($log->metadata))
                                <div style="margin-top:6px; display:flex; flex-wrap:wrap; gap:6px;">
                                    @if(isset($log->metadata['old_status']) && isset($log->metadata['new_status']))
                                        <span style="font-size:10px; background:#eff6ff; color:#1e40af; border-radius:6px; padding:2px 8px; font-weight:700;">
                                            {{ $log->metadata['old_status'] }} ➔ {{ $log->metadata['new_status'] }}
                                        </span>
                                    @endif
                                    @if(isset($log->metadata['admin_level']))
                                        <span style="font-size:10px; background:#e0e7ff; color:#3730a3; border-radius:6px; padding:2px 8px; font-weight:700;">
                                            مستوى: {{ $log->metadata['admin_level'] }}
                                        </span>
                                    @endif
                                    @if(isset($log->metadata['tier_2_tasks_threshold']))
                                        <span style="font-size:10px; background:#f0fdfa; color:#115e59; border-radius:6px; padding:2px 8px; font-weight:700;">
                                            T2: {{ $log->metadata['tier_2_tasks_threshold'] }}م / {{ $log->metadata['tier_2_rating_threshold'] }}★
                                        </span>
                                    @endif
                                    @if(isset($log->metadata['tier_3_tasks_threshold']))
                                        <span style="font-size:10px; background:#fdf2f8; color:#9d174d; border-radius:6px; padding:2px 8px; font-weight:700;">
                                            T3: {{ $log->metadata['tier_3_tasks_threshold'] }}م / {{ $log->metadata['tier_3_rating_threshold'] }}★
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:54px 20px; color:#9ca3af;">
                            <i class="fa-solid fa-clock-rotate-left" style="font-size:38px; margin-bottom:12px; display:block; color:#cbd5e1;"></i>
                            <div style="font-weight:700; font-size:14px; color:#6b7280;">لا توجد أي سجلات تدقيق مطابقة للبحث.</div>
                            @if(request()->anyFilled(['search', 'action', 'admin_id', 'date_from', 'date_to']))
                                <a href="{{ route('admin.audit-log.index') }}" style="display:inline-block; margin-top:10px; color:#354e20; font-weight:700; text-decoration:none; font-size:12px;">
                                    إلغاء عوامل التصفية
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($logs->hasPages())
        <div style="padding:16px 20px; border-top:1px solid #f3f4f6;">
            {{ $logs->links() }}
        </div>
    @endif
</div>

</x-admin-layout>
