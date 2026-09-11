<x-admin-layout>

{{-- إحصائيات سريعة للمستخدمين --}}
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-bottom:24px;">
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border:1px solid #e2dcd0; border-top:4px solid #354e20;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">إجمالي المستخدمين</span>
        <div style="font-size:26px; font-weight:900; color:#1a1f36;">{{ number_format($stats['total']) }}</div>
    </div>
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border:1px solid #e2dcd0; border-top:4px solid #10b981;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">الحسابات النشطة (المعتمدة)</span>
        <div style="font-size:26px; font-weight:900; color:#065f46;">{{ number_format($stats['approved']) }}</div>
    </div>
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border:1px solid #e2dcd0; border-top:4px solid #ef4444;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">الحسابات الموقوفة</span>
        <div style="font-size:26px; font-weight:900; color:#991b1b;">{{ number_format($stats['suspended']) }}</div>
    </div>
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border:1px solid #e2dcd0; border-top:4px solid #f59e0b;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">بانتظار الاعتماد</span>
        <div style="font-size:26px; font-weight:900; color:#b45309;">{{ number_format($stats['pending']) }}</div>
    </div>
</div>

{{-- فلاتر البحث والدور والحالة --}}
<div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.06); margin-bottom:20px; border:1px solid #e2dcd0;">
    <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:1; min-width:220px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">بحث بالاسم أو البريد</label>
            <input name="search" value="{{ request('search') }}" placeholder="ابحث عن اسم أو بريد..."
                style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit; box-sizing:border-box;">
        </div>

        <div style="min-width:180px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">الدور</label>
            <select name="role" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit;">
                <option value="">كافة الأدوار</option>
                <option value="provider" {{ request('role') === 'provider' ? 'selected' : '' }}>مقدم خدمة (متطوع)</option>
                <option value="elder"    {{ request('role') === 'elder'    ? 'selected' : '' }}>كبير سن (مستفيد)</option>
                <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>مدير نظام</option>
            </select>
        </div>

        <div style="min-width:180px;">
            <label style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:5px;">حالة الحساب</label>
            <select name="status" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:13px; font-family:inherit;">
                <option value="">كافة الحالات</option>
                <option value="approved"  {{ request('status') === 'approved'  ? 'selected' : '' }}>نشط (معتمد)</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>موقوف</option>
                <option value="pending"   {{ request('status') === 'pending'   ? 'selected' : '' }}>بانتظار الاعتماد</option>
                <option value="rejected"  {{ request('status') === 'rejected'  ? 'selected' : '' }}>مرفوض</option>
            </select>
        </div>

        <button type="submit" style="background:#354e20; color:#fff; border:none; border-radius:10px; padding:10px 22px; font-size:13px; font-weight:700; cursor:pointer; font-family:inherit; display:flex; align-items:center; gap:6px;">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span>تصفية</span>
        </button>

        @if (request()->anyFilled(['search', 'role', 'status']))
        <a href="{{ route('admin.users.index') }}" style="background:#f3f4f6; color:#374151; border-radius:10px; padding:10px 18px; font-size:13px; font-weight:700; text-decoration:none; display:flex; align-items:center; gap:6px;">
            <i class="fa-solid fa-xmark"></i>
            <span>إعادة ضبط</span>
        </a>
        @endif
    </form>
</div>

{{-- جدول المستخدمين --}}
<div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
    <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h3 style="margin:0; font-size:15px; font-weight:900; color:#1a1f36;">قائمة حسابات المستخدمين</h3>
            <p style="margin:4px 0 0; font-size:11px; color:#9ca3af;">إجمالي المستخدمين المطابقين: {{ $users->total() }}</p>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; font-size:13px;">
            <thead>
                <tr style="background:#fcfbf9; border-bottom:1px solid #e2dcd0;">
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">المستخدم</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الدور</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الحالة</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الهاتف / المدينة</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">تاريخ الانضمام</th>
                    <th style="padding:12px 20px; text-align:right; font-weight:700; color:#6b7280;">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                @php
                    $roleKey = $u->getRoleAttribute();
                    $roleConfig = [
                        'elder'       => ['bg'=>'#eff6ff','color'=>'#1e40af','label'=>'كبير سن'],
                        'provider'    => ['bg'=>'#e6edd9','color'=>'#354e20','label'=>'مقدم خدمة'],
                        'admin'       => ['bg'=>'#f5f3ff','color'=>'#6d28d9','label'=>'مدير نظام'],
                        'super_admin' => ['bg'=>'#fef3c7','color'=>'#92400e','label'=>'مدير أعلى'],
                    ];
                    $rc = $roleConfig[$roleKey] ?? ['bg'=>'#f3f4f6','color'=>'#4b5563','label'=>$roleKey];

                    $statusConfig = [
                        'approved'  => ['bg'=>'#d1fae5','color'=>'#065f46','label'=>'نشط (معتمد)'],
                        'suspended' => ['bg'=>'#fee2e2','color'=>'#991b1b','label'=>'موقوف ✗'],
                        'pending'   => ['bg'=>'#fef3c7','color'=>'#92400e','label'=>'بانتظار الاعتماد'],
                        'rejected'  => ['bg'=>'#f3f4f6','color'=>'#4b5563','label'=>'مرفوض'],
                    ];
                    $sc = $statusConfig[$u->status] ?? ['bg'=>'#f3f4f6','color'=>'#6b7280','label'=>$u->status];
                @endphp
                <tr style="border-top:1px solid #f3f4f6; transition:background 0.15s;" onmouseover="this.style.background='#faf9f5'" onmouseout="this.style.background='transparent'">
                    <td style="padding:14px 20px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:38px; height:38px; border-radius:50%; background:{{ $roleKey === 'provider' ? '#354e20' : ($roleKey === 'elder' ? '#83a55b' : '#2d3748') }}; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:14px;">
                                {{ mb_substr($u->name, 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight:800; color:#1a1f36;">{{ $u->name }}</div>
                                <div style="font-size:11px; color:#9ca3af;">{{ $u->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:14px 20px;">
                        <span style="background:{{ $rc['bg'] }}; color:{{ $rc['color'] }}; padding:3px 10px; border-radius:8px; font-size:11px; font-weight:700;">
                            {{ $rc['label'] }}
                        </span>
                    </td>
                    <td style="padding:14px 20px;">
                        <span style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }}; padding:3px 10px; border-radius:8px; font-size:11px; font-weight:800;">
                            {{ $sc['label'] }}
                        </span>
                    </td>
                    <td style="padding:14px 20px; font-size:12px; color:#4b5563;">
                        <div>{{ $u->getPhoneNumberAttribute() ?? '—' }}</div>
                        <div style="font-size:11px; color:#9ca3af;">{{ $u->elderProfile?->city ?? '—' }}</div>
                    </td>
                    <td style="padding:14px 20px; font-size:11.5px; color:#6b7280; white-space:nowrap;">
                        {{ $u->created_at->format('Y/m/d') }}
                    </td>
                    <td style="padding:14px 20px; white-space:nowrap;">
                        <a href="{{ route('admin.users.show', $u) }}"
                            style="background:#e6edd9; color:#354e20; padding:6px 14px; border-radius:10px; font-size:11.5px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:5px;">
                            <i class="fa-solid fa-id-badge"></i>
                            <span>عرض الملف</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:48px 20px; text-align:center; color:#9ca3af;">
                        <i class="fa-regular fa-user" style="font-size:36px; margin-bottom:8px; display:block;"></i>
                        لا يوجد مستخدمون يطابقون خيارات البحث
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
    <div style="padding:16px 20px; border-top:1px solid #f3f4f6;">
        {{ $users->links() }}
    </div>
    @endif
</div>

</x-admin-layout>
