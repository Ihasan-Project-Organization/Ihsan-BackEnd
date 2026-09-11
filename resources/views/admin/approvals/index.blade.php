<x-admin-layout>

{{-- فلاتر البحث والدور --}}
<div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.06); margin-bottom:20px; border:1px solid #e2dcd0;">
    <form method="GET" action="{{ route('admin.approvals.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:1; min-width:220px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">بحث بالاسم أو البريد</label>
            <input name="search" value="{{ request('search') }}" placeholder="ابحث عن اسم أو بريد المستخدم..."
                style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit; box-sizing:border-box;">
        </div>
        <div style="min-width:180px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">نوع الحساب</label>
            <select name="role" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit;">
                <option value="">كافة الأدوار</option>
                <option value="provider" {{ request('role') === 'provider' ? 'selected' : '' }}>مقدم خدمة (متطوع)</option>
                <option value="elder"    {{ request('role') === 'elder' ? 'selected' : '' }}>كبير سن (مستفيد)</option>
            </select>
        </div>
        <button type="submit" style="background:#354e20; color:#fff; border:none; border-radius:10px; padding:10px 22px; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; display:flex; align-items:center; gap:6px;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>تصفية</span>
        </button>
        @if (request()->anyFilled(['search', 'role']))
        <a href="{{ route('admin.approvals.index') }}" style="background:#f3f4f6; color:#374151; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:6px;">
            <i class="fa-solid fa-xmark"></i>
            <span>إلغاء الفلتر</span>
        </a>
        @endif
    </form>
</div>

{{-- جدول الحسابات المعلقة --}}
<div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
    <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h3 style="margin:0; font-size:15px; font-weight:900; color:#1a1f36;">طلبات اعتماد الحسابات الجديدة</h3>
            <p style="margin:4px 0 0; font-size:11px; color:#9ca3af;">إجمالي الطلبات المعلقة التي تتطلب مراجعة المستندات: {{ $pendingUsers->total() }}</p>
        </div>
        <span style="background:#fef3c7; color:#b45309; padding:4px 12px; border-radius:20px; font-size:11px; font-weight:800;">
            {{ $pendingUsers->total() }} بانتظار المراجعة
        </span>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:#fcfbf9; border-bottom:1px solid #e2dcd0;">
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">المستخدم</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">نوع الحساب</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الهاتف / المدينة</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">تاريخ التسجيل</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">حالة الطلب</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingUsers as $u)
                @php
                    $isProv = $u->isProvider();
                @endphp
                <tr style="border-top:1px solid #f3f4f6; transition:background 0.15s;" onmouseover="this.style.background='#faf9f5'" onmouseout="this.style.background='transparent'">
                    <td style="padding:14px 20px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:50%; background:{{ $isProv ? '#354e20' : '#83a55b' }}; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px;">
                                {{ mb_substr($u->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight:800; color:#1a1f36;">{{ $u->name }}</div>
                                <div style="font-size:11px; color:#9ca3af;">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:14px 20px;">
                        @if ($isProv)
                            <span style="background:#e6edd9; color:#354e20; padding:3px 10px; border-radius:8px; font-size:11px; font-weight:700;">
                                <i class="fa-solid fa-hand-holding-heart" style="margin-left:4px;"></i> متطوع (مقدم خدمة)
                            </span>
                        @else
                            <span style="background:#eff6ff; color:#1e40af; padding:3px 10px; border-radius:8px; font-size:11px; font-weight:700;">
                                <i class="fa-solid fa-person-cane" style="margin-left:4px;"></i> كبير سن (مستفيد)
                            </span>
                        @endif
                    </td>
                    <td style="padding:14px 20px; color:#4b5563; font-size:12px;">
                        <div>{{ $u->getPhoneNumberAttribute() ?? '—' }}</div>
                        <div style="font-size:11px; color:#9ca3af;">{{ $u->elderProfile?->city ?? '—' }}</div>
                    </td>
                    <td style="padding:14px 20px; color:#6b7280; font-size:12px; white-space:nowrap;">
                        {{ $u->created_at->format('Y/m/d') }}
                        <span style="display:block; font-size:10px; color:#9ca3af;">{{ $u->created_at->diffForHumans() }}</span>
                    </td>
                    <td style="padding:14px 20px;">
                        <span style="background:#fef3c7; color:#92400e; padding:4px 10px; border-radius:8px; font-size:11px; font-weight:700;">
                            <i class="fa-solid fa-hourglass-half" style="margin-left:3px;"></i> بانتظار الاعتماد
                        </span>
                    </td>
                    <td style="padding:14px 20px;">
                        <a href="{{ route('admin.approvals.show', $u) }}"
                            style="background:#354e20; color:#fff; padding:7px 16px; border-radius:10px; font-size:11.5px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:6px; transition:opacity 0.2s;"
                            onmouseover="this.style.opacity='0.9';"
                            onmouseout="this.style.opacity='1';">
                            <i class="fa-solid fa-eye"></i>
                            <span>معاينة المستندات والبت</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:48px 20px; text-align:center; color:#9ca3af;">
                        <i class="fa-solid fa-clipboard-check" style="font-size:36px; color:#10b981; margin-bottom:12px; display:block;"></i>
                        <span style="font-size:14px; font-weight:700; color:#374151;">رائع! لا توجد حسابات معلقة بانتظار الاعتماد.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($pendingUsers->hasPages())
    <div style="padding:16px 20px; border-top:1px solid #f3f4f6;">
        {{ $pendingUsers->links() }}
    </div>
    @endif
</div>

</x-admin-layout>
