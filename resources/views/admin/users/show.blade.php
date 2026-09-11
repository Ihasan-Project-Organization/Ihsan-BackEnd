<x-admin-layout>

<div style="margin-bottom:16px; display:flex; align-items:center; justify-content:space-between;">
    <a href="{{ route('admin.users.index') }}" style="font-size:13px; color:#354e20; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:6px;">
        <i class="fa-solid fa-arrow-right"></i>
        <span>العودة لقائمة المستخدمين</span>
    </a>

    @if ($user->status === 'suspended')
        <span style="background:#fee2e2; color:#991b1b; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:800;">
            <i class="fa-solid fa-ban" style="margin-left:4px;"></i> الحساب موقوف عن الاستخدام
        </span>
    @elseif ($user->status === 'approved')
        <span style="background:#d1fae5; color:#065f46; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:800;">
            <i class="fa-solid fa-check" style="margin-left:4px;"></i> الحساب نشط ومعتمد
        </span>
    @else
        <span style="background:#fef3c7; color:#92400e; padding:4px 14px; border-radius:20px; font-size:12px; font-weight:800;">
            {{ $user->status }}
        </span>
    @endif
</div>

@if ($errors->any())
<div style="background:#fee2e2; border:1px solid #fca5a5; color:#991b1b; border-radius:12px; padding:14px 18px; margin-bottom:20px; font-size:13px;">
    <ul style="margin:0; padding-right:20px;">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- تنبيه خاص إذا كان الحساب موقوفاً يوضح سبب الإيقاف --}}
@if ($user->status === 'suspended')
<div style="background:#fef2f2; border:1px solid #fecaca; border-radius:16px; padding:18px 22px; margin-bottom:24px; display:flex; align-items:flex-start; gap:14px;">
    <div style="width:42px; height:42px; border-radius:10px; background:#fee2e2; color:#dc2626; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">
        <i class="fa-solid fa-ban"></i>
    </div>
    <div style="flex:1;">
        <h4 style="margin:0 0 4px; font-size:14px; font-weight:900; color:#991b1b;">تم إيقاف هذا الحساب ومُنِع المستخدم من الدخول</h4>
        <div style="font-size:12.5px; color:#7f1d1d; line-height:1.6; background:rgba(255,255,255,0.7); padding:10px 14px; border-radius:8px; margin-top:6px;">
            <strong>سبب الإيقاف المسجل:</strong> {{ $user->suspension_reason ?? 'لم يُحدد سبب مفصل' }}
        </div>
    </div>
</div>
@endif

<div style="display:grid; grid-template-columns: 340px 1fr; gap:24px; align-items:start;">

    {{-- العمود الأيمن: البطاقة الشخصية + إدارة حالة الحساب --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- بطاقة البروفايل --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            @php
                $role = $user->getRoleAttribute();
            @endphp
            <div style="background:linear-gradient(135deg, #354e20 0%, #4e6b35 100%); padding:28px 20px; text-align:center;">
                <div style="width:72px; height:72px; border-radius:50%; background:rgba(255,255,255,0.15); color:#fff; display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:900; margin:0 auto 12px;">
                    {{ mb_substr($user->name, 0, 1) }}
                </div>
                <div style="font-size:17px; font-weight:900; color:#fff;">{{ $user->name }}</div>
                <div style="font-size:12px; color:#dfe6d5; margin-top:4px;">{{ $user->email }}</div>

                <div style="margin-top:10px;">
                    @if ($role === 'provider')
                        <span style="background:rgba(255,255,255, 0.2); color:#ffffff; border:1px solid rgba(255,255,255, 0.3); padding:3px 12px; border-radius:20px; font-size:11px; font-weight:800;">
                            متطوع (مقدم خدمة)
                        </span>
                    @elseif ($role === 'elder')
                        <span style="background:rgba(255, 255, 255, 0.2); color:#ffffff; border:1px solid rgba(255, 255, 255, 0.3); padding:3px 12px; border-radius:20px; font-size:11px; font-weight:800;">
                            كبير سن (مستفيد)
                        </span>
                    @else
                        <span style="background:rgba(244, 164, 0, 0.3); color:#fde68a; border:1px solid rgba(244, 164, 0, 0.5); padding:3px 12px; border-radius:20px; font-size:11px; font-weight:800;">
                            مدير نظام
                        </span>
                    @endif
                </div>
            </div>

            <div style="padding:20px;">
                <div style="display:flex; flex-direction:column; gap:12px; font-size:13px;">
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span style="color:#6b7280;">الحالة الحالية</span>
                        <span style="font-weight:800; color:{{ $user->status === 'approved' ? '#10b981' : ($user->status === 'suspended' ? '#ef4444' : '#f59e0b') }};">
                            {{ $user->status === 'approved' ? 'نشط ومعتمد ✓' : ($user->status === 'suspended' ? 'موقوف ✗' : $user->status) }}
                        </span>
                    </div>

                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span style="color:#6b7280;">رقم الهاتف</span>
                        <span style="font-weight:700; color:#1a1f36; direction:ltr;">{{ $user->getPhoneNumberAttribute() ?? '—' }}</span>
                    </div>

                    @if ($user->elderProfile)
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span style="color:#6b7280;">المدينة</span>
                        <span style="font-weight:700; color:#1a1f36;">{{ $user->elderProfile->city ?? '—' }}</span>
                    </div>
                    @endif

                    @if ($user->serviceProviderProfile)
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span style="color:#6b7280;">متوسط التقييم</span>
                        <span style="font-weight:800; color:#f59e0b;">⭐ {{ number_format($user->serviceProviderProfile->average_rating ?? 0, 1) }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span style="color:#6b7280;">المستوى التطوعي</span>
                        <span style="font-weight:800; color:#354e20;">Tier {{ $user->serviceProviderProfile->tier }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span style="color:#6b7280;">المهام المنجزة</span>
                        <span style="font-weight:700; color:#1a1f36;">{{ $user->serviceProviderProfile->completed_tasks_count }}</span>
                    </div>
                    @endif

                    <div style="display:flex; justify-content:space-between; border-bottom:1px solid #f3f4f6; padding-bottom:8px;">
                        <span style="color:#6b7280;">إجمالي الطلبات</span>
                        <span style="font-weight:700; color:#1a1f36;">{{ $requestsCount }} ({{ $completedRequestsCount }} مكتمل)</span>
                    </div>

                    <div style="display:flex; justify-content:space-between;">
                        <span style="color:#6b7280;">تاريخ الانضمام</span>
                        <span style="font-weight:700; color:#1a1f36;">{{ $user->created_at->format('Y/m/d') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- بطاقة التحكم بالحساب: إيقاف / إعادة تفعيل --}}
        @if (!$user->admin)
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); padding:20px; border:1px solid #e2dcd0;">
            <div style="font-size:13px; font-weight:900; color:#1a1f36; margin-bottom:12px; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-user-shield" style="color:#354e20;"></i>
                <span>التحكم الإداري بالحساب</span>
            </div>

            @if ($user->status === 'suspended')
                {{-- زر إعادة التفعيل --}}
                <form method="POST" action="{{ route('admin.users.reactivate', $user) }}">
                    @csrf
                    <p style="font-size:11.5px; color:#6b7280; margin:0 0 12px; line-height:1.5;">
                        إلغاء تعليق الحساب وإعادة منحه الصلاحية الكاملة لتسجيل الدخول واستخدام المنصة.
                    </p>
                    <button type="submit"
                        style="width:100%; background:#10b981; color:#fff; border:none; border-radius:10px; padding:12px; font-size:13px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:6px;"
                        onclick="return confirm('هل أنت متأكد من إعادة تفعيل حساب {{ $user->name }}؟')">
                        <i class="fa-solid fa-rotate-left"></i>
                        <span>إعادة تفعيل الحساب الآن</span>
                    </button>
                </form>
            @else
                {{-- نموذج إيقاف الحساب مع سبب إلزامي --}}
                <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                    @csrf
                    <p style="font-size:11.5px; color:#6b7280; margin:0 0 10px; line-height:1.5;">
                        إيقاف الحساب فوراً وطرده من الجلسة النشطة ومنعه من الدخول مجدداً مع إظهار سبب الإيقاف.
                    </p>
                    <div style="margin-bottom:12px;">
                        <label style="font-size:11px; font-weight:700; color:#dc2626; display:block; margin-bottom:5px;">سبب الإيقاف (إلزامي)*</label>
                        <textarea name="suspension_reason" rows="3" required
                            placeholder="اكتب سبب التعليق الصريح (مثال: تكرار الشكاوى وعدم الالتزام بسياسات المنصة)..."
                            style="width:100%; border:1px solid #fca5a5; border-radius:10px; padding:9px 12px; font-size:12px; font-family:inherit; resize:vertical; box-sizing:border-box;">{{ old('suspension_reason') }}</textarea>
                    </div>
                    <button type="submit"
                        style="width:100%; background:#ef4444; color:#fff; border:none; border-radius:10px; padding:11px; font-size:12.5px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:6px;"
                        onclick="return confirm('تحذير: سيتم إيقاف حساب {{ $user->name }} ومنعه فوراً من الدخول. تأكيد؟')">
                        <i class="fa-solid fa-ban"></i>
                        <span>إيقاف الحساب ومنع الدخول</span>
                    </button>
                </form>
            @endif
        </div>
        @endif

    </div>

    {{-- العمود الأيسر: سجل الشكاوى والحوادث المرتبطة بالمستخدم --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- 1. الشكاوى المسجلة ضد هذا المستخدم (على خدماته أو طلباته) --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            <div style="padding:16px 20px; border-bottom:1px solid #f3f4f6; display:flex; justify-content:space-between; align-items:center;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;"></i>
                    <h3 style="margin:0; font-size:14px; font-weight:900; color:#1a1f36;">الشكاوى المسجلة ضد المستخدم ({{ $complaintsAgainst->count() }})</h3>
                </div>
            </div>
            <div style="padding:8px 0;">
                @forelse ($complaintsAgainst as $c)
                <div style="padding:14px 20px; border-bottom:1px solid #f9fafb; display:flex; justify-content:space-between; align-items:flex-start; gap:14px;">
                    <div style="flex:1;">
                        <div style="font-size:12px; font-weight:800; color:#374151;">
                            من الشاكي: {{ $c->reporter?->name ?? 'مجهول' }}
                            <span style="font-size:10.5px; color:#9ca3af; font-weight:600;">· {{ $c->created_at->format('Y/m/d') }}</span>
                        </div>
                        <div style="font-size:12.5px; color:#6b7280; margin-top:3px; line-height:1.5;">{{ $c->description }}</div>
                    </div>
                    <a href="{{ route('admin.complaints.show', $c) }}"
                        style="background:#fee2e2; color:#991b1b; padding:5px 12px; border-radius:8px; font-size:11px; font-weight:700; text-decoration:none; white-space:nowrap;">
                        فحص الشكوى
                    </a>
                </div>
                @empty
                <div style="padding:28px 20px; text-align:center; color:#9ca3af; font-size:12px;">
                    <i class="fa-solid fa-circle-check" style="font-size:24px; color:#10b981; margin-bottom:6px; display:block;"></i>
                    لا توجد أي شكاوى مسجلة ضد هذا المستخدم
                </div>
                @endforelse
            </div>
        </div>

        {{-- 2. سجل حوادث عدم الموثوقية (خاص بمقدم الخدمة) --}}
        @if ($user->serviceProviderProfile)
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            <div style="padding:16px 20px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-clock-rotate-left" style="color:#f59e0b;"></i>
                <h3 style="margin:0; font-size:14px; font-weight:900; color:#1a1f36;">سجل حوادث عدم الموثوقية للمتطوع ({{ $reliabilityIncidents->count() }})</h3>
            </div>
            <div style="padding:8px 0;">
                @forelse ($reliabilityIncidents as $inc)
                <div style="padding:12px 20px; border-bottom:1px solid #f9fafb; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-size:12px; font-weight:800; color:#374151;">
                            {{ $inc->incident_type === 'apology' ? 'اعتذار مفاجئ عن المهمة' : 'تأخر عن الموعد المحدد' }}
                        </div>
                        <div style="font-size:10.5px; color:#9ca3af;">{{ $inc->created_at->format('Y/m/d - h:i A') }} ({{ $inc->created_at->diffForHumans() }})</div>
                    </div>
                    @if ($inc->request)
                    <a href="{{ route('admin.requests.show', $inc->request) }}"
                        style="color:#354e20; font-size:11.5px; font-weight:700; text-decoration:none;">
                        {{ $inc->request->public_id ?? '#'.$inc->request->id }} ←
                    </a>
                    @endif
                </div>
                @empty
                <div style="padding:28px 20px; text-align:center; color:#9ca3af; font-size:12px;">
                    <i class="fa-solid fa-shield-check" style="font-size:24px; color:#10b981; margin-bottom:6px; display:block;"></i>
                    سجل الموثوقية نظيف ولا توجد حوادث مسجلة
                </div>
                @endforelse
            </div>
        </div>
        @endif

        {{-- 3. الشكاوى التي قدمها المستخدم بنفسه --}}
        @if ($complaintsSubmitted->isNotEmpty())
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            <div style="padding:16px 20px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-flag" style="color:#354e20;"></i>
                <h3 style="margin:0; font-size:14px; font-weight:900; color:#1a1f36;">بلاغات قدمها المستخدم ({{ $complaintsSubmitted->count() }})</h3>
            </div>
            <div style="padding:8px 0;">
                @foreach ($complaintsSubmitted as $cs)
                <div style="padding:12px 20px; border-bottom:1px solid #f9fafb; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-size:12.5px; color:#374151;">{{ Str::limit($cs->description, 50) }}</div>
                        <div style="font-size:10.5px; color:#9ca3af;">{{ $cs->created_at->format('Y/m/d') }}</div>
                    </div>
                    <a href="{{ route('admin.complaints.show', $cs) }}"
                        style="color:#354e20; font-size:11px; font-weight:700; text-decoration:none;">
                        عرض الشكوى ←
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- 4. آخر الإشعارات المرسلة للحساب --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            <div style="padding:16px 20px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:8px;">
                <i class="fa-regular fa-bell" style="color:#6b7280;"></i>
                <h3 style="margin:0; font-size:14px; font-weight:900; color:#1a1f36;">آخر الإشعارات المرسلة للمستخدم</h3>
            </div>
            <div style="padding:8px 0;">
                @forelse ($user->notifications as $notif)
                <div style="padding:12px 20px; border-bottom:1px solid #f9fafb; font-size:12.5px;">
                    <div style="color:#374151; font-weight:{{ $notif->is_read ? '500' : '700' }};">{{ $notif->message }}</div>
                    <div style="font-size:10px; color:#9ca3af; margin-top:2px;">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
                @empty
                <div style="padding:20px; text-align:center; color:#9ca3af; font-size:12px;">لا توجد إشعارات سابقة</div>
                @endforelse
            </div>
        </div>

    </div>

</div>

</x-admin-layout>
