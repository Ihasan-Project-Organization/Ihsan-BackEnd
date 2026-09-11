<x-admin-layout>

{{-- الإحصائيات السريعة للمديرين --}}
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:24px;">
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #354e20;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">إجمالي مسؤولي النظام</span>
        <div style="font-size:26px; font-weight:900; color:#1a1f36;">{{ number_format($stats['total']) }}</div>
    </div>
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #f59e0b;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">مدراء النظام الأعلى (Super Admin)</span>
        <div style="font-size:26px; font-weight:900; color:#b45309;">{{ number_format($stats['super_admin']) }}</div>
    </div>
    <div style="background:#fff; border-radius:16px; padding:18px 20px; box-shadow:0 1px 6px rgba(0,0,0,0.05); border-top:4px solid #83a55b;">
        <span style="font-size:11px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">مدراء النظام (Admin)</span>
        <div style="font-size:26px; font-weight:900; color:#354e20;">{{ number_format($stats['regular']) }}</div>
    </div>
</div>

{{-- شريط العنوان وزر الإضافة --}}
<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:20px;">
    <div>
        <h2 style="margin:0; font-size:20px; font-weight:900; color:#1a1f36;">إدارة مسؤولي النظام (Admins)</h2>
        <p style="margin:4px 0 0; font-size:12px; color:#6b7280;">استعراض وإدارة حسابات مدراء منصة إحسان ومنح الصلاحيات الإدارية.</p>
    </div>

    <a href="{{ route('admin.admins.create') }}"
        style="background:linear-gradient(135deg, #354e20, #4e6b35); color:#fff; text-decoration:none; border-radius:12px; padding:11px 22px; font-size:13px; font-weight:700; display:inline-flex; align-items:center; gap:8px; box-shadow:0 2px 8px rgba(53,78,32,0.25);">
        <i class="fa-solid fa-user-plus"></i>
        <span>إضافة مدير جديد</span>
    </a>
</div>

{{-- جدول المديرين --}}
<div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden;">
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:right; font-size:13px;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:1px solid #e5e7eb; color:#4b5563;">
                    <th style="padding:14px 20px; font-weight:800;">المدير</th>
                    <th style="padding:14px 20px; font-weight:800;">البريد الإلكتروني</th>
                    <th style="padding:14px 20px; font-weight:800;">مستوى الصلاحية</th>
                    <th style="padding:14px 20px; font-weight:800;">تاريخ التعيين</th>
                    <th style="padding:14px 20px; font-weight:800; text-align:center;">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                    <tr style="border-bottom:1px solid #f3f4f6; transition:background 0.15s;" onmouseover="this.style.background='#faf9f5'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div style="width:38px; height:38px; border-radius:10px; background:{{ $admin->admin_level === 'super_admin' ? '#fef3c7' : '#e6edd9' }}; color:{{ $admin->admin_level === 'super_admin' ? '#d97706' : '#354e20' }}; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:14px;">
                                    <i class="fa-solid {{ $admin->admin_level === 'super_admin' ? 'fa-crown' : 'fa-user-shield' }}"></i>
                                </div>
                                <div>
                                    <div style="font-weight:800; color:#1a1f36; font-size:13px;">{{ $admin->user?->name ?? 'مستخدم محذوف' }}</div>
                                    @if ($admin->user_id === $currentAdminUserId)
                                        <span style="font-size:10px; color:#10b981; font-weight:700;">(أنت الآن)</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td style="padding:14px 20px; color:#4b5563; font-weight:600;">
                            {{ $admin->user?->email ?? '—' }}
                        </td>

                        <td style="padding:14px 20px;">
                            @if ($admin->admin_level === 'super_admin')
                                <span style="background:#fef3c7; color:#92400e; border:1px solid #fde68a; border-radius:20px; padding:4px 12px; font-size:11px; font-weight:800; display:inline-flex; align-items:center; gap:5px;">
                                    <i class="fa-solid fa-crown" style="font-size:10px; color:#d97706;"></i>
                                    مدير أعلى (Super Admin)
                                </span>
                            @else
                                <span style="background:#e6edd9; color:#354e20; border:1px solid #b8cfa0; border-radius:20px; padding:4px 12px; font-size:11px; font-weight:800; display:inline-flex; align-items:center; gap:5px;">
                                    <i class="fa-solid fa-shield-halved" style="font-size:10px; color:#354e20;"></i>
                                    مدير نظام (Admin)
                                </span>
                            @endif
                        </td>

                        <td style="padding:14px 20px; color:#6b7280; font-size:12px;">
                            {{ $admin->created_at ? $admin->created_at->translatedFormat('Y/m/d - h:i A') : '—' }}
                        </td>

                        <td style="padding:14px 20px; text-align:center;">
                            @if ($admin->user_id === $currentAdminUserId)
                                <span style="background:#f3f4f6; color:#9ca3af; border-radius:8px; padding:6px 14px; font-size:11px; font-weight:700; display:inline-block;">
                                    <i class="fa-solid fa-lock" style="margin-left:4px;"></i>
                                    حسابك الحالي
                                </span>
                            @else
                                <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" style="display:inline-block;"
                                      onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف حساب هذا المدير ({{ $admin->user?->name }}) نهائياً؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        style="background:#fef2f2; color:#ef4444; border:1px solid #fecaca; border-radius:8px; padding:6px 14px; font-size:11px; font-weight:800; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; gap:5px; transition:0.15s;"
                                        onmouseover="this.style.background='#ef4444'; this.style.color='#fff';"
                                        onmouseout="this.style.background='#fef2f2'; this.style.color='#ef4444';">
                                        <i class="fa-solid fa-trash-can"></i>
                                        <span>حذف</span>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:48px 20px; color:#9ca3af;">
                            <i class="fa-solid fa-users-slash" style="font-size:36px; margin-bottom:12px; display:block; color:#cbd5e1;"></i>
                            <div style="font-weight:700; font-size:14px;">لا يوجد أي مدراء مسجلين حالياً.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</x-admin-layout>
