<x-admin-layout>

<div style="margin-bottom:20px; display:flex; align-items:center; justify-content:space-between;">
    <a href="{{ route('admin.approvals.index') }}" style="font-size:13px; color:#354e20; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:6px;">
        <i class="fa-solid fa-arrow-right"></i>
        <span>العودة لقائمة طلبات الاعتماد</span>
    </a>

    <span style="background:#fef3c7; color:#92400e; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:800;">
        <i class="fa-solid fa-clock" style="margin-left:4px;"></i> بانتظار قرار الإدارة
    </span>
</div>

@if ($errors->any())
<div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; border-radius:12px; padding:14px 18px; margin-bottom:20px; font-size:13px;">
    <div style="font-weight:800; margin-bottom:6px;"><i class="fa-solid fa-triangle-exclamation" style="margin-left:6px;"></i> يرجى تصحيح الأخطاء التالية:</div>
    <ul style="margin:0; padding-right:20px;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if ($user->resubmission_note)
<div style="background:#fffbeb; border:1px solid #fde68a; border-radius:14px; padding:16px 20px; margin-bottom:24px;">
    <div style="display:flex; align-items:center; gap:8px; font-size:13px; font-weight:800; color:#92400e; margin-bottom:6px;">
        <i class="fa-solid fa-comment-dots"></i>
        <span>آخر طلب استكمال مستندات تم إرساله للمستخدم:</span>
    </div>
    <div style="font-size:13px; color:#78350f; line-height:1.6; background:rgba(255,255,255,0.6); padding:10px 14px; border-radius:8px;">
        {{ $user->resubmission_note }}
    </div>
</div>
@endif

<div class="admin-detail-grid admin-detail-grid-sidebar-left">

    {{-- العمود الأيمن: بيانات التسجيل والحساب --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- بطاقة الهوية الشخصية --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            <div style="background:linear-gradient(135deg, #354e20 0%, #4e6b35 100%); padding:24px 20px; text-align:center;">
                <div style="width:70px; height:70px; border-radius:50%; background:rgba(255,255,255,0.15); color:#fff; display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:900; margin:0 auto 12px;">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
                <div style="font-size:17px; font-weight:900; color:#fff;">{{ $user->name }}</div>
                <div style="font-size:12px; color:#dfe6d5; margin-top:4px;">{{ $user->email }}</div>

                <div style="margin-top:12px;">
                    @if ($user->isProvider())
                        <span style="background:rgba(255, 255, 255, 0.2); color:#ffffff; border:1px solid rgba(255, 255, 255, 0.3); padding:4px 14px; border-radius:20px; font-size:11px; font-weight:800;">
                            <i class="fa-solid fa-hand-holding-heart" style="margin-left:4px;"></i> متطوع (مقدم خدمة)
                        </span>
                    @else
                        <span style="background:rgba(255, 255, 255, 0.2); color:#ffffff; border:1px solid rgba(255, 255, 255, 0.3); padding:4px 14px; border-radius:20px; font-size:11px; font-weight:800;">
                            <i class="fa-solid fa-person-cane" style="margin-left:4px;"></i> كبير سن (مستفيد)
                        </span>
                    @endif
                </div>
            </div>

            <div style="padding:20px;">
                <div style="display:flex; flex-direction:column; gap:14px; font-size:13px;">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span style="color:#6b7280;">الاسم الكامل</span>
                        <span style="font-weight:700; color:#1a1f36;">{{ $user->isProvider() ? ($user->serviceProviderProfile?->full_name ?? $user->name) : ($user->elderProfile?->full_name ?? $user->name) }}</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span style="color:#6b7280;">رقم الهاتف / الجوال</span>
                        <span style="font-weight:700; color:#1a1f36; direction:ltr;">{{ $user->isProvider() ? ($user->serviceProviderProfile?->phone_number ?? $user->getPhoneNumberAttribute() ?? '—') : ($user->elderProfile?->phone_number ?? $user->getPhoneNumberAttribute() ?? '—') }}</span>
                    </div>

                    @if ($user->isProvider() && $user->serviceProviderProfile)
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                            <span style="color:#6b7280;">تاريخ الميلاد</span>
                            <span style="font-weight:700; color:#1a1f36;">{{ $user->serviceProviderProfile->birth_date?->format('Y/m/d') ?? '—' }}</span>
                        </div>

                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                            <span style="color:#6b7280;">رقم الهوية الوطنية</span>
                            <span style="font-weight:700; color:#1a1f36; font-family:monospace;">{{ $user->serviceProviderProfile->id_number ?? '—' }}</span>
                        </div>
                    @endif

                    @if ($user->elderProfile)
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                            <span style="color:#6b7280;">رقم الهوية الوطنية</span>
                            <span style="font-weight:700; color:#1a1f36; font-family:monospace;">{{ $user->elderProfile->id_number ?? '—' }}</span>
                        </div>

                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                            <span style="color:#6b7280;">المدينة / المحافظة</span>
                            <span style="font-weight:700; color:#1a1f36;">{{ $user->elderProfile->city ?? '—' }}</span>
                        </div>

                        @if ($user->elderProfile->address)
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                            <span style="color:#6b7280;">العنوان التفصيلي</span>
                            <span style="font-weight:700; color:#1a1f36;">{{ $user->elderProfile->address }}</span>
                        </div>
                        @endif

                        @if ($user->elderProfile->housing_type)
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                            <span style="color:#6b7280;">نوع السكن</span>
                            <span style="font-weight:700; color:#1a1f36;">{{ match($user->elderProfile->housing_type) { 'independent' => 'منزل مستقل', 'apartment' => 'شقة سكنية', 'with_family' => 'مع العائلة', default => $user->elderProfile->housing_type } }}</span>
                        </div>
                        @endif

                        @if ($user->elderProfile->birth_date)
                        <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                            <span style="color:#6b7280;">تاريخ الميلاد</span>
                            <span style="font-weight:700; color:#1a1f36;">{{ $user->elderProfile->birth_date->format('Y/m/d') }}</span>
                        </div>
                        @endif
                    @endif

                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span style="color:#6b7280;">توثيق البريد</span>
                        <span style="font-weight:700; color:{{ $user->hasVerifiedEmail() ? '#10b981' : '#f59e0b' }};">
                            {{ $user->hasVerifiedEmail() ? 'موثّق ✓' : 'غير موثّق ⚠' }}
                        </span>
                    </div>

                    <div style="display:flex; justify-content:space-between;">
                        <span style="color:#6b7280;">تاريخ التسجيل</span>
                        <span style="font-weight:700; color:#1a1f36;">{{ $user->created_at->format('Y/m/d H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- إجراء سريع 1: اعتماد الحساب --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); padding:20px; border:1px solid #e2dcd0; border-top:4px solid #10b981;">
            <div style="font-size:14px; font-weight:900; color:#065f46; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-circle-check"></i>
                <span>الموافقة والاعتماد</span>
            </div>
            <p style="font-size:11.5px; color:#6b7280; margin:0 0 14px; line-height:1.5;">
                تغيير حالة الحساب فوراً إلى "معتمد" (approved) وإرسال إشعار للمستخدم ليتمكن من استخدام كامل المنصة.
            </p>
            <form method="POST" action="{{ route('admin.approvals.approve', $user) }}">
                @csrf
                <button type="submit"
                    style="width:100%; background:#10b981; color:#fff; border:none; border-radius:12px; padding:12px; font-size:13px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:8px; transition:background 0.2s;"
                    onmouseover="this.style.background='#059669';"
                    onmouseout="this.style.background='#10b981';"
                    onclick="return confirm('هل أنت متأكد من اعتماد حساب {{ $user->name }}؟')">
                    <i class="fa-solid fa-check"></i>
                    <span>اعتماد وتفعيل الحساب</span>
                </button>
            </form>
        </div>

    </div>

    {{-- العمود الأيسر: المستندات المرفوعة + نموذجي الرفض وطلب الاستكمال --}}
    <div style="display:flex; flex-direction:column; gap:24px;">

        {{-- قسم المستندات المرفوعة لمعاينتها --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-file-shield" style="color:#354e20; font-size:16px;"></i>
                <h3 style="margin:0; font-size:15px; font-weight:900; color:#1a1f36;">المستندات المرفوعة للمعاينة والتدقيق</h3>
            </div>

            <div style="padding:24px;">
                @php
                    $idDocPath = $user->isProvider()
                        ? $user->serviceProviderProfile?->id_document_path
                        : $user->elderProfile?->id_document_path;
                    $conductDocPath = $user->isProvider()
                        ? $user->serviceProviderProfile?->good_conduct_cert_path
                        : null;
                @endphp

                <div style="display:grid; grid-template-columns: {{ $user->isProvider() ? '1fr 1fr' : '1fr' }}; gap:20px;">

                    {{-- 1. صورة الهوية الوطنية --}}
                    <div style="border:1px solid #e5e7eb; border-radius:14px; padding:16px; background:#f9fafb;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <span style="font-weight:800; font-size:13px; color:#1a1f36;">
                                <i class="fa-solid fa-id-card" style="margin-left:6px; color:#354e20;"></i>
                                صورة الهوية الشخصية
                            </span>
                            @if ($idDocPath)
                                <a href="{{ route('admin.documents.view', ['path' => $idDocPath]) }}" target="_blank"
                                    style="font-size:11px; color:#354e20; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px;">
                                    <span>فتح كامل</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i>
                                </a>
                            @endif
                        </div>

                        @if ($idDocPath)
                            @php $isPdf = str_ends_with(strtolower($idDocPath), '.pdf'); @endphp
                            @if ($isPdf)
                                <div style="height:220px; background:#fff; border:1px dashed #cbd5e1; border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px;">
                                    <i class="fa-solid fa-file-pdf" style="font-size:48px; color:#ef4444;"></i>
                                    <span style="font-size:12px; color:#64748b; font-weight:700;">مستند بصيغة PDF</span>
                                    <a href="{{ route('admin.documents.view', ['path' => $idDocPath]) }}" target="_blank"
                                        style="background:#354e20; color:#fff; padding:6px 16px; border-radius:8px; font-size:11.5px; font-weight:700; text-decoration:none;">
                                        معاينة وتحميل المستند
                                    </a>
                                </div>
                            @else
                                <div style="height:220px; background:#fff; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; border:1px solid #e2e8f0;">
                                    <img src="{{ route('admin.documents.view', ['path' => $idDocPath]) }}" alt="الهوية"
                                        style="max-width:100%; max-height:100%; object-fit:contain; cursor:pointer;"
                                        onclick="window.open('{{ route('admin.documents.view', ['path' => $idDocPath]) }}', '_blank');">
                                </div>
                            @endif
                        @else
                            <div style="height:220px; background:#fff; border:1px dashed #cbd5e1; border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#9ca3af; font-size:12px;">
                                <i class="fa-regular fa-image" style="font-size:36px; margin-bottom:8px;"></i>
                                لم يتم رفع صورة هوية
                            </div>
                        @endif
                    </div>

                    {{-- 2. شهادة حسن السيرة والسلوك (تظهر فقط لمقدم الخدمة / المتطوع) --}}
                    @if ($user->isProvider())
                    <div style="border:1px solid #e5e7eb; border-radius:14px; padding:16px; background:#f9fafb;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                            <span style="font-weight:800; font-size:13px; color:#1a1f36;">
                                <i class="fa-solid fa-certificate" style="margin-left:6px; color:#10b981;"></i>
                                شهادة حسن السيرة والسلوك
                            </span>
                            @if ($conductDocPath)
                                <a href="{{ route('admin.documents.view', ['path' => $conductDocPath]) }}" target="_blank"
                                    style="font-size:11px; color:#354e20; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:4px;">
                                    <span>فتح كامل</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:9px;"></i>
                                </a>
                            @endif
                        </div>

                        @if ($conductDocPath)
                            @php $isPdfConduct = str_ends_with(strtolower($conductDocPath), '.pdf'); @endphp
                            @if ($isPdfConduct)
                                <div style="height:220px; background:#fff; border:1px dashed #cbd5e1; border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px;">
                                    <i class="fa-solid fa-file-pdf" style="font-size:48px; color:#ef4444;"></i>
                                    <span style="font-size:12px; color:#64748b; font-weight:700;">شهادة بصيغة PDF</span>
                                    <a href="{{ route('admin.documents.view', ['path' => $conductDocPath]) }}" target="_blank"
                                        style="background:#10b981; color:#fff; padding:6px 16px; border-radius:8px; font-size:11.5px; font-weight:700; text-decoration:none;">
                                        معاينة وتحميل الشهادة
                                    </a>
                                </div>
                            @else
                                <div style="height:220px; background:#fff; border-radius:10px; overflow:hidden; display:flex; align-items:center; justify-content:center; border:1px solid #e2e8f0;">
                                    <img src="{{ route('admin.documents.view', ['path' => $conductDocPath]) }}" alt="شهادة حسن السيرة"
                                        style="max-width:100%; max-height:100%; object-fit:contain; cursor:pointer;"
                                        onclick="window.open('{{ route('admin.documents.view', ['path' => $conductDocPath]) }}', '_blank');">
                                </div>
                            @endif
                        @else
                            <div style="height:220px; background:#fff; border:1px dashed #cbd5e1; border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; color:#9ca3af; font-size:12px;">
                                <i class="fa-regular fa-file-lines" style="font-size:36px; margin-bottom:8px;"></i>
                                لم يتم رفع شهادة حسن السيرة
                            </div>
                        @endif
                    </div>
                    @endif

                </div>
            </div>
        </div>

        {{-- قرار الرفض مع سبب إلزامي (تمت إزالة استكمال المستندات لحصر الإجراء في الاعتماد أو الرفض) --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); padding:20px; border:1px solid #e2dcd0; border-top:4px solid #ef4444;">
            <div style="font-size:14px; font-weight:900; color:#991b1b; margin-bottom:8px; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-ban"></i>
                <span>رفض طلب الاعتماد</span>
            </div>
            <p style="font-size:11.5px; color:#6b7280; margin:0 0 12px; line-height:1.5;">
                تغيير الحالة إلى "مرفوض" (rejected) مع سبب إلزامي يُعرض للمستخدم ويُمنع من المنصة.
            </p>
            <form method="POST" action="{{ route('admin.approvals.reject', $user) }}">
                @csrf
                <div style="margin-bottom:12px;">
                    <textarea name="rejection_reason" rows="3" required
                        placeholder="اكتب سبب الرفض الإلزامي للمستخدم (مثال: البيانات المدخلة غير مطابقة للوثائق الرسمية)..."
                        style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; font-size:12.5px; font-family:inherit; resize:vertical; box-sizing:border-box;">{{ old('rejection_reason') }}</textarea>
                </div>
                <button type="submit"
                    style="width:100%; background:#ef4444; color:#fff; border:none; border-radius:10px; padding:11px; font-size:12.5px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:6px;"
                    onclick="return confirm('تأكيد رفض طلب اعتماد {{ $user->name }}؟')">
                    <i class="fa-solid fa-xmark"></i>
                    <span>رفض الحساب نهائياً</span>
                </button>
            </form>
        </div>

    </div>

</div>

</x-admin-layout>
