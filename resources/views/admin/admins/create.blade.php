<x-admin-layout>

<div style="max-width:760px; margin:0 auto;">
    {{-- مسار التنقل والرجوع --}}
    <div style="margin-bottom:20px;">
        <a href="{{ route('admin.admins.index') }}"
            style="color:#6b7280; font-size:12px; font-weight:700; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
            <i class="fa-solid fa-arrow-right"></i>
            <span>العودة إلى قائمة المديرين</span>
        </a>
    </div>

    {{-- بطاقة النموذج الرئيسية --}}
    <div style="background:#fff; border-radius:18px; box-shadow:0 2px 10px rgba(0,0,0,0.06); padding:28px 32px; border:1px solid #e2dcd0;">
        <div style="display:flex; align-items:center; gap:14px; margin-bottom:24px; padding-bottom:18px; border-bottom:1px solid #f3f4f6;">
            <div style="width:48px; height:48px; border-radius:12px; background:linear-gradient(135deg, #354e20, #4e6b35); color:#fff; display:flex; align-items:center; justify-content:center; font-size:20px; box-shadow:0 4px 10px rgba(53,78,32,0.25);">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div>
                <h2 style="margin:0; font-size:18px; font-weight:900; color:#1a1f36;">إضافة مدير نظام جديد</h2>
                <p style="margin:4px 0 0; font-size:12px; color:#6b7280;">قم بتعبئة بيانات المدير وتحديد الصلاحيات الممنوحة له في منصة إحسان.</p>
            </div>
        </div>

        @if ($errors->any())
            <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:14px 16px; margin-bottom:20px;">
                <div style="font-weight:800; color:#991b1b; font-size:13px; margin-bottom:6px; display:flex; align-items:center; gap:6px;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>يرجى تصحيح الأخطاء التالية:</span>
                </div>
                <ul style="margin:0; padding-right:20px; color:#b91c1c; font-size:12px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.admins.store') }}">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:18px;">
                {{-- الاسم الكامل --}}
                <div style="grid-column: span 2;">
                    <label style="display:block; font-size:12px; font-weight:800; color:#374151; margin-bottom:6px;">
                        الاسم الكامل <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="مثال: د. محمد العبدالله"
                        style="width:100%; border:1px solid #d1d5db; border-radius:10px; padding:11px 14px; font-size:13px; font-family:inherit; box-sizing:border-box; outline:none; transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='#83a55b';" onblur="this.style.borderColor='#d1d5db';">
                </div>

                {{-- البريد الإلكتروني --}}
                <div style="grid-column: span 2;">
                    <label style="display:block; font-size:12px; font-weight:800; color:#374151; margin-bottom:6px;">
                        البريد الإلكتروني <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="admin@ehsan.sa"
                        style="width:100%; border:1px solid #d1d5db; border-radius:10px; padding:11px 14px; font-size:13px; font-family:inherit; box-sizing:border-box; outline:none; transition:border-color 0.2s;"
                        onfocus="this.style.borderColor='#83a55b';" onblur="this.style.borderColor='#d1d5db';">
                </div>

                {{-- كلمة المرور --}}
                <div>
                    <label style="display:block; font-size:12px; font-weight:800; color:#374151; margin-bottom:6px;">
                        كلمة المرور <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="password" name="password" required
                        placeholder="••••••••"
                        style="width:100%; border:1px solid #d1d5db; border-radius:10px; padding:11px 14px; font-size:13px; font-family:inherit; box-sizing:border-box; outline:none;"
                        onfocus="this.style.borderColor='#83a55b';" onblur="this.style.borderColor='#d1d5db';">
                    <span style="font-size:10px; color:#6b7280; display:block; margin-top:4px;">8 أحرف على الأقل.</span>
                </div>

                {{-- تأكيد كلمة المرور --}}
                <div>
                    <label style="display:block; font-size:12px; font-weight:800; color:#374151; margin-bottom:6px;">
                        تأكيد كلمة المرور <span style="color:#ef4444;">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required
                        placeholder="••••••••"
                        style="width:100%; border:1px solid #d1d5db; border-radius:10px; padding:11px 14px; font-size:13px; font-family:inherit; box-sizing:border-box; outline:none;"
                        onfocus="this.style.borderColor='#83a55b';" onblur="this.style.borderColor='#d1d5db';">
                </div>
            </div>

            {{-- مستوى الصلاحية (الدور) --}}
            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:12px; font-weight:800; color:#374151; margin-bottom:10px;">
                    مستوى الصلاحية الإدارية <span style="color:#ef4444;">*</span>
                </label>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                    {{-- مدير عادي --}}
                    <label style="cursor:pointer;">
                        <div style="border:2px solid {{ old('admin_level', 'admin') === 'admin' ? '#354e20' : '#e5e7eb' }}; border-radius:12px; padding:14px; background:{{ old('admin_level', 'admin') === 'admin' ? '#e6edd9' : '#fff' }}; transition:all 0.15s;"
                             id="admin-card">
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                                <input type="radio" name="admin_level" value="admin" {{ old('admin_level', 'admin') === 'admin' ? 'checked' : '' }}
                                    onchange="document.getElementById('admin-card').style.borderColor='#354e20'; document.getElementById('admin-card').style.background='#e6edd9'; document.getElementById('super-card').style.borderColor='#e5e7eb'; document.getElementById('super-card').style.background='#fff';">
                                <span style="font-weight:800; font-size:13px; color:#1a1f36;">مدير نظام (Admin)</span>
                            </div>
                            <p style="margin:0; font-size:11px; color:#6b7280; padding-right:24px; line-height:1.5;">
                                صلاحيات إدارة الطلبات والشكاوى ومراجعة اعتمادات الحسابات وحظر المستخدمين.
                            </p>
                        </div>
                    </label>

                    {{-- مدير أعلى --}}
                    <label style="cursor:pointer;">
                        <div style="border:2px solid {{ old('admin_level') === 'super_admin' ? '#f59e0b' : '#e5e7eb' }}; border-radius:12px; padding:14px; background:{{ old('admin_level') === 'super_admin' ? '#fffbeb' : '#fff' }}; transition:all 0.15s;"
                             id="super-card">
                            <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                                <input type="radio" name="admin_level" value="super_admin" {{ old('admin_level') === 'super_admin' ? 'checked' : '' }}
                                    onchange="document.getElementById('super-card').style.borderColor='#f59e0b'; document.getElementById('super-card').style.background='#fffbeb'; document.getElementById('admin-card').style.borderColor='#e5e7eb'; document.getElementById('admin-card').style.background='#fff';">
                                <span style="font-weight:800; font-size:13px; color:#92400e;">مدير أعلى (Super Admin)</span>
                            </div>
                            <p style="margin:0; font-size:11px; color:#6b7280; padding-right:24px; line-height:1.5;">
                                كافة الصلاحيات بالإضافة إلى تعيين وحذف المديرين وضبط إعدادات وعتبات النظام.
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            {{-- أزرار الإجراءات --}}
            <div style="display:flex; justify-content:flex-start; gap:12px; padding-top:16px; border-top:1px solid #f3f4f6;">
                <button type="submit"
                    style="background:#354e20; color:#fff; border:none; border-radius:10px; padding:11px 28px; font-size:13px; font-weight:800; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; gap:8px; box-shadow:0 2px 8px rgba(53,78,32,0.25);">
                    <i class="fa-solid fa-check"></i>
                    <span>حفظ وإضافة المدير</span>
                </button>

                <a href="{{ route('admin.admins.index') }}"
                    style="background:#f3f4f6; color:#4b5563; text-decoration:none; border-radius:10px; padding:11px 22px; font-size:13px; font-weight:700; display:inline-flex; align-items:center;">
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

</x-admin-layout>
