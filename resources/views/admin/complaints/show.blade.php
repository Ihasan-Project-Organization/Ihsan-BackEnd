<x-admin-layout>

<div style="margin-bottom:16px; display:flex; align-items:center; justify-content:space-between;">
    <a href="{{ route('admin.complaints.index') }}" style="font-size:13px; color:#354e20; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:6px;">
        <i class="fa-solid fa-arrow-right"></i>
        <span>العودة لقائمة الشكاوى</span>
    </a>

    <div>
        @if ($complaint->status === 'open')
            <span style="background:#fee2e2; color:#991b1b; padding:5px 14px; border-radius:10px; font-size:12px; font-weight:800;">
                شكوى مفتوحة (تتطلب معالجة)
            </span>
        @elseif ($complaint->status === 'closed')
            <span style="background:#d1fae5; color:#065f46; padding:5px 14px; border-radius:10px; font-size:12px; font-weight:800;">
                شكوى مغلقة ومحسومة ✓
            </span>
        @else
            <span style="background:#fef3c7; color:#92400e; padding:5px 14px; border-radius:10px; font-size:12px; font-weight:800;">
                قيد المراجعة والتحقيق
            </span>
        @endif
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 340px; gap:24px; align-items:start;">

    {{-- تفاصيل الشكوى والطلب المرتبط --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- بطاقة الشكوى --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            <div style="background:linear-gradient(135deg, #354e20 0%, #4e6b35 100%); padding:20px 24px; color:#fff;">
                <div style="font-size:12px; color:#dfe6d5; font-weight:700;">شكوى وبلاغ رقم #{{ $complaint->id }}</div>
                <h2 style="margin:4px 0 0; font-size:18px; font-weight:900;">تفاصيل الشكوى المقدمة</h2>
                <div style="font-size:11.5px; color:#b8cfa0; margin-top:4px;">
                    تاريخ التسجيل: {{ $complaint->created_at->format('Y/m/d - h:i A') }} ({{ $complaint->created_at->diffForHumans() }})
                </div>
            </div>

            <div style="padding:24px;">
                {{-- مقدم الشكوى --}}
                <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:16px; border-bottom:1px solid #f3f4f6; margin-bottom:16px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:44px; height:44px; border-radius:50%; background:#354e20; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:16px;">
                            {{ mb_substr($complaint->reporter?->name ?? '؟', 0, 1) }}
                        </div>
                        <div>
                            <div style="font-size:14px; font-weight:800; color:#1a1f36;">{{ $complaint->reporter?->name ?? 'مجهول' }}</div>
                            <div style="font-size:11px; color:#6b7280;">{{ $complaint->reporter?->email }}</div>
                        </div>
                    </div>
                </div>

                {{-- نص الشكوى --}}
                <div style="margin-bottom:20px;">
                    <span style="font-size:11.5px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">نص ووصف الشكوى:</span>
                    <div style="background:#f9fafb; border-radius:12px; border:1px solid #f3f4f6; padding:18px; font-size:13.5px; color:#374151; line-height:1.7;">
                        {{ $complaint->description }}
                    </div>
                </div>

                {{-- الطلب المرتبط --}}
                @if ($complaint->serviceRequest)
                <div>
                    <span style="font-size:11.5px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">الطلب المرتبط بهذه الشكوى:</span>
                    <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:16px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                        <div>
                            <div style="font-weight:900; font-size:14px; color:#1e40af;">
                                {{ $complaint->serviceRequest->title }}
                            </div>
                            <div style="font-size:11.5px; color:#3b82f6; margin-top:2px;">
                                المعرف: {{ $complaint->serviceRequest->public_id ?? '#'.$complaint->serviceRequest->id }}
                                · الحالة: {{ $complaint->serviceRequest->status }}
                            </div>
                        </div>
                        <a href="{{ route('admin.requests.show', $complaint->serviceRequest) }}"
                            style="background:#354e20; color:#fff; padding:8px 18px; border-radius:10px; font-size:12px; font-weight:800; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                            <span>معاينة الطلب بالكامل</span>
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- العمود الأيسر: الإجراء الإداري وملاحظات المشرف --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); padding:24px; border:1px solid #e2dcd0; border-top:4px solid #354e20;">
            <div style="font-size:14px; font-weight:900; color:#1a1f36; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-gavel" style="color:#354e20;"></i>
                <span>القرار الإداري والحل</span>
            </div>

            <form method="POST" action="{{ route('admin.complaints.resolve', $complaint) }}">
                @csrf

                <div style="margin-bottom:16px;">
                    <label style="font-size:11.5px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">حالة الشكوى</label>
                    <select name="status" id="complaint_status_select"
                        style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; font-size:13px; font-family:inherit;">
                        <option value="open"         {{ $complaint->status === 'open'         ? 'selected' : '' }}>مفتوحة</option>
                        <option value="under_review" {{ $complaint->status === 'under_review' ? 'selected' : '' }}>قيد المراجعة</option>
                        <option value="closed"       {{ $complaint->status === 'closed'       ? 'selected' : '' }}>مغلقة (تم الحل)</option>
                    </select>
                </div>

                <div style="margin-bottom:18px;">
                    <label style="font-size:11.5px; font-weight:700; color:#6b7280; display:block; margin-bottom:6px;">ملاحظات الإدارة وقرار الحل</label>
                    <textarea name="admin_notes" rows="6"
                        placeholder="سجّل تفاصيل التحقيق، والتواصل مع الأطراف، والإجراء المتخذ لإغلاق الشكوى..."
                        style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:10px 12px; font-size:12.5px; font-family:inherit; resize:vertical; box-sizing:border-box;">{{ old('admin_notes', $complaint->admin_notes) }}</textarea>
                </div>

                <button type="submit"
                    style="width:100%; background:#354e20; color:#fff; border:none; border-radius:10px; padding:12px; font-size:13px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:10px;">
                    <i class="fa-solid fa-save"></i>
                    <span>حفظ القرار الإداري</span>
                </button>
            </form>

            {{-- زر إغلاق فوري إذا كانت مفتوحة --}}
            @if ($complaint->status !== 'closed')
            <form method="POST" action="{{ route('admin.complaints.resolve', $complaint) }}" style="margin-top:12px; padding-top:12px; border-top:1px dashed #e5e7eb;">
                @csrf
                <input type="hidden" name="status" value="closed">
                <input type="hidden" name="admin_notes" value="{{ $complaint->admin_notes ?? 'تم إغلاق الشكوى واعتبارها منتهية.' }}">
                <button type="submit"
                    style="width:100%; background:#ecfdf5; color:#065f46; border:1px solid #6ee7b7; border-radius:10px; padding:10px; font-size:12px; font-weight:800; cursor:pointer; font-family:inherit; display:flex; align-items:center; justify-content:center; gap:6px;"
                    onclick="return confirm('تأكيد إغلاق هذه الشكوى نهائياً؟')">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>تغيير الحالة إلى "مغلقة" مباشرة</span>
                </button>
            </form>
            @endif
        </div>

    </div>

</div>

</x-admin-layout>
