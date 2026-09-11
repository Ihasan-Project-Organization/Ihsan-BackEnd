<x-admin-layout>

{{-- تبويبات الشاشة: الشكاوى vs تنبيهات الموثوقية --}}
<div style="display:flex; gap:12px; margin-bottom:24px; border-bottom:1px solid #e5e7eb; padding-bottom:12px;">
    <a href="{{ route('admin.complaints.index', ['tab' => 'complaints']) }}"
        style="text-decoration:none; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; display:flex; align-items:center; gap:8px; transition:all 0.2s; {{ $tab !== 'reliability' ? 'background:#354e20; color:#fff; box-shadow:0 4px 12px rgba(53,78,32,0.2);' : 'background:#fff; color:#4b5563; border:1px solid #e5e7eb;' }}">
        <i class="fa-solid fa-flag"></i>
        <span>البلاغات والشكاوى</span>
        @if ($openComplaintsCount > 0)
            <span style="background:{{ $tab !== 'reliability' ? 'rgba(255,255,255,0.25)' : '#fee2e2; color:#991b1b;' }}; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:800;">
                {{ $openComplaintsCount }} مفتوحة
            </span>
        @endif
    </a>

    <a href="{{ route('admin.complaints.index', ['tab' => 'reliability']) }}"
        style="text-decoration:none; padding:10px 20px; border-radius:12px; font-size:13px; font-weight:800; display:flex; align-items:center; gap:8px; transition:all 0.2s; {{ $tab === 'reliability' ? 'background:#83a55b; color:#fff; box-shadow:0 4px 12px rgba(131,165,91,0.25);' : 'background:#fff; color:#4b5563; border:1px solid #e5e7eb;' }}">
        <i class="fa-solid fa-shield-virus"></i>
        <span>تنبيهات الموثوقية (آخر 30 يوماً)</span>
        @if ($reliabilityAlertsCount > 0)
            <span style="background:{{ $tab === 'reliability' ? 'rgba(255,255,255,0.25)' : '#fef3c7; color:#b45309;' }}; padding:2px 8px; border-radius:10px; font-size:11px; font-weight:800;">
                {{ $reliabilityAlertsCount }} متطوع
            </span>
        @endif
    </a>
</div>

{{-- محتوى التبويب 1: الشكاوى والبلاغات --}}
@if ($tab !== 'reliability')

    {{-- فلاتر الشكاوى --}}
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.06); margin-bottom:20px; border:1px solid #e2dcd0;">
        <form method="GET" action="{{ route('admin.complaints.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
            <input type="hidden" name="tab" value="complaints">

            <div style="flex:1; min-width:220px;">
                <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">بحث باسم مقدم الشكوى</label>
                <input name="search" value="{{ request('search') }}" placeholder="اسم صاحب الشكوى..."
                    style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit; box-sizing:border-box;">
            </div>

            <div style="min-width:180px;">
                <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">حالة الشكوى</label>
                <select name="status" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit;">
                    <option value="">كافة الحالات</option>
                    <option value="open"         {{ request('status') === 'open'         ? 'selected' : '' }}>مفتوحة (جديدة)</option>
                    <option value="under_review" {{ request('status') === 'under_review' ? 'selected' : '' }}>قيد المراجعة والتحقيق</option>
                    <option value="closed"       {{ request('status') === 'closed'       ? 'selected' : '' }}>مغلقة (تم الحل)</option>
                </select>
            </div>

            <button type="submit" style="background:#354e20; color:#fff; border:none; border-radius:10px; padding:10px 22px; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-magnifying-glass"></i>
                <span>تصفية</span>
            </button>

            @if (request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.complaints.index', ['tab' => 'complaints']) }}" style="background:#f3f4f6; color:#374151; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-xmark"></i>
                <span>إلغاء الفلتر</span>
            </a>
            @endif
        </form>
    </div>

    {{-- جدول الشكاوى --}}
    <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
        <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="margin:0; font-size:15px; font-weight:900; color:#1a1f36;">سجل الشكاوى والبلاغات</h3>
                <p style="margin:4px 0 0; font-size:11px; color:#9ca3af;">إجمالي الشكاوى المسجلة: {{ $complaints->total() }}</p>
            </div>
        </div>

        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead>
                    <tr style="background:#fcfbf9; border-bottom:1px solid #e2dcd0;">
                        <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">#</th>
                        <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">مقدم الشكوى</th>
                        <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">وصف الشكوى</th>
                        <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الطلب المرتبط</th>
                        <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الحالة</th>
                        <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">التاريخ</th>
                        <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الإجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($complaints as $c)
                    <tr style="border-top:1px solid #f3f4f6; transition:background 0.15s;" onmouseover="this.style.background='#faf9f5'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px; color:#9ca3af; font-size:11px;">#{{ $c->id }}</td>
                        <td style="padding:14px 20px;">
                            <div style="font-weight:800; color:#1a1f36;">{{ $c->reporter?->name ?? 'مجهول' }}</div>
                            <div style="font-size:11px; color:#9ca3af;">{{ $c->reporter?->email }}</div>
                        </td>
                        <td style="padding:14px 20px; max-width:280px;">
                            <div style="overflow:hidden; text-overflow:ellipsis; white-space:nowrap; color:#374151;">
                                {{ Str::limit($c->description, 65) }}
                            </div>
                        </td>
                        <td style="padding:14px 20px; white-space:nowrap;">
                            @if ($c->serviceRequest)
                                <a href="{{ route('admin.requests.show', $c->serviceRequest) }}" style="color:#354e20; font-weight:700; text-decoration:none; direction:ltr; text-align:right;">
                                    {{ $c->serviceRequest->public_id ?? '#'.$c->serviceRequest->id }}
                                </a>
                            @else
                                <span style="color:#9ca3af;">—</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px; white-space:nowrap;">
                            @if ($c->status === 'open')
                                <span style="background:#fee2e2; color:#991b1b; padding:3px 10px; border-radius:8px; font-size:11px; font-weight:800;">مفتوحة</span>
                            @elseif ($c->status === 'closed')
                                <span style="background:#d1fae5; color:#065f46; padding:3px 10px; border-radius:8px; font-size:11px; font-weight:800;">مغلقة ✓</span>
                            @else
                                <span style="background:#fef3c7; color:#92400e; padding:3px 10px; border-radius:8px; font-size:11px; font-weight:800;">قيد المراجعة</span>
                            @endif
                        </td>
                        <td style="padding:14px 20px; color:#6b7280; font-size:11.5px; white-space:nowrap;">
                            {{ $c->created_at->format('Y/m/d') }}
                        </td>
                        <td style="padding:14px 20px; white-space:nowrap;">
                            <a href="{{ route('admin.complaints.show', $c) }}"
                                style="background:#e6edd9; color:#354e20; padding:6px 14px; border-radius:10px; font-size:11.5px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:5px;">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>معاينة والحل</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="padding:48px 20px; text-align:center; color:#9ca3af;">
                            <i class="fa-solid fa-shield-halved" style="font-size:36px; color:#10b981; margin-bottom:12px; display:block;"></i>
                            <span>لا توجد شكاوى مسجلة تطابق الفلاتر الحالية</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($complaints->hasPages())
        <div style="padding:16px 20px; border-top:1px solid #f3f4f6;">
            {{ $complaints->links() }}
        </div>
        @endif
    </div>

{{-- محتوى التبويب 2: تنبيهات الموثوقية (Reliability Alerts) --}}
@else

    <div style="background:#fffbeb; border:1px solid #fde68a; border-radius:16px; padding:18px 22px; margin-bottom:24px; display:flex; align-items:flex-start; gap:14px;">
        <div style="width:40px; height:40px; border-radius:10px; background:#fef3c7; color:#b45309; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h4 style="margin:0 0 4px; font-size:14px; font-weight:900; color:#92400e;">نظام مراقبة جودة وموثوقية المتطوعين</h4>
            <p style="margin:0; font-size:12px; color:#78350f; line-height:1.6;">
                يعرض هذا التبويب كافة مقدمي الخدمة الذين بلغوا <strong>3 حوادث عدم موثوقية أو أكثر (اعتذار متأخر أو تأخير عن الموعد) خلال آخر 30 يوماً</strong>. لا يوجد إجراء آلي هنا، بل روابط مباشرة لملف كل مستخدم لتمكين المشرف من اتخاذ القرار اليدوي المناسب (تنبيه، أو تعليق مؤقت).
            </p>
        </div>
    </div>

    @if ($flaggedProviders->count() > 0)
        <div style="display:flex; flex-direction:column; gap:20px;">
            @foreach ($flaggedProviders as $prov)
            <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); border:1px solid #fee2e2; overflow:hidden;">
                {{-- رأس البطاقة: بيانات المتطوع والتقييم --}}
                <div style="background:#fff5f5; padding:18px 24px; border-bottom:1px solid #fee2e2; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div style="width:48px; height:48px; border-radius:50%; background:#ef4444; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:18px;">
                            {{ mb_substr($prov->user?->name ?? '؟', 0, 1) }}
                        </div>
                        <div>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <h3 style="margin:0; font-size:16px; font-weight:900; color:#1a1f36;">{{ $prov->user?->name }}</h3>
                                <span style="background:#fee2e2; color:#991b1b; padding:3px 10px; border-radius:12px; font-size:11px; font-weight:800;">
                                    {{ $prov->reliabilityIncidents->count() }} حوادث موثوقية بآخر 30 يوماً
                                </span>
                            </div>
                            <div style="font-size:11.5px; color:#6b7280; margin-top:3px;">
                                {{ $prov->user?->email }} · {{ $prov->phone_number }} · المستوى (Tier {{ $prov->tier }})
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:16px;">
                        <div style="text-align:center; background:#fff; padding:8px 16px; border-radius:10px; border:1px solid #fed7d7;">
                            <div style="font-size:10px; color:#6b7280; font-weight:700;">متوسط التقييم</div>
                            <div style="font-size:15px; font-weight:900; color:#f59e0b;">
                                ⭐ {{ number_format($prov->average_rating ?? 0, 1) }}
                            </div>
                        </div>

                        {{-- رابط لملف المستخدم --}}
                        <a href="{{ Route::has('admin.users.show') ? route('admin.users.show', $prov->user) : '#' }}"
                            style="background:#354e20; color:#fff; padding:9px 18px; border-radius:10px; font-size:12px; font-weight:800; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-user-gear"></i>
                            <span>عرض ملف المستخدم واتخاذ قرار</span>
                        </a>
                    </div>
                </div>

                {{-- تفاصيل حوادث المتطوع في آخر 30 يوماً --}}
                <div style="padding:20px 24px;">
                    <h5 style="margin:0 0 12px; font-size:12.5px; font-weight:800; color:#6b7280;">سجل الحوادث المسجلة خلال الـ 30 يوماً الأخيرة:</h5>
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse; font-size:12.5px;">
                            <thead>
                                <tr style="background:#fcfbf9; border-bottom:1px solid #e2dcd0;">
                                    <th style="padding:10px 16px; text-align:right; font-weight:700; color:#6b7280;">التاريخ والتوقيت</th>
                                    <th style="padding:10px 16px; text-align:right; font-weight:700; color:#6b7280;">نوع الحادثة</th>
                                    <th style="padding:10px 16px; text-align:right; font-weight:700; color:#6b7280;">الطلب المرتبط</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prov->reliabilityIncidents as $inc)
                                <tr style="border-top:1px solid #f3f4f6;">
                                    <td style="padding:10px 16px; font-weight:700; color:#374151;">
                                        {{ $inc->created_at->format('Y/m/d - h:i A') }}
                                        <span style="font-size:10px; color:#9ca3af; margin-right:6px;">({{ $inc->created_at->diffForHumans() }})</span>
                                    </td>
                                    <td style="padding:10px 16px;">
                                        @if ($inc->incident_type === 'apology')
                                            <span style="background:#fce7f3; color:#9d174d; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700;">
                                                <i class="fa-solid fa-circle-xmark" style="margin-left:4px;"></i> اعتذار مفاجئ عن المهمة
                                            </span>
                                        @elseif ($inc->incident_type === 'delay')
                                            <span style="background:#ffedd5; color:#c2410c; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700;">
                                                <i class="fa-solid fa-clock-rotate-left" style="margin-left:4px;"></i> تأخر عن الموعد المحدد
                                            </span>
                                        @else
                                            <span style="background:#f3f4f6; color:#4b5563; padding:3px 10px; border-radius:6px; font-size:11px; font-weight:700;">
                                                {{ $inc->incident_type }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding:10px 16px;">
                                        @if ($inc->request)
                                            <a href="{{ route('admin.requests.show', $inc->request) }}" style="color:#354e20; font-weight:700; text-decoration:none; direction:ltr; text-align:right;">
                                                {{ $inc->request->public_id ?? '#'.$inc->request->id }}
                                            </a>
                                            <span style="font-size:11px; color:#9ca3af; margin-right:6px;">({{ $inc->request->title }})</span>
                                        @else
                                            <span style="color:#9ca3af;">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div style="background:#fff; border-radius:16px; padding:60px 20px; text-align:center; box-shadow:0 1px 6px rgba(0,0,0,0.06);">
            <div style="width:60px; height:60px; border-radius:50%; background:#d1fae5; color:#059669; display:flex; align-items:center; justify-content:center; font-size:28px; margin:0 auto 16px;">
                <i class="fa-solid fa-shield-heart"></i>
            </div>
            <h3 style="margin:0 0 6px; font-size:16px; font-weight:900; color:#1a1f36;">سجل الموثوقية نظيف تماماً!</h3>
            <p style="margin:0; font-size:12.5px; color:#6b7280;">لا يوجد أي مقدم خدمة وصل إلى عتبة الـ 3 حوادث عدم موثوقية خلال آخر 30 يوماً.</p>
        </div>
    @endif

@endif

</x-admin-layout>
