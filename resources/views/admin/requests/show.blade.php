<x-admin-layout>

<div style="margin-bottom:16px; display:flex; align-items:center; justify-content:space-between;">
    <a href="{{ route('admin.requests.index') }}" style="font-size:13px; color:#354e20; text-decoration:none; font-weight:700; display:inline-flex; align-items:center; gap:6px;">
        <i class="fa-solid fa-arrow-right"></i>
        <span>العودة لقائمة الطلبات</span>
    </a>
</div>

@php
    $statusConfig = [
        'pending_acceptance'   => ['bg'=>'#fef3c7','color'=>'#92400e','label'=>'في الانتظار'],
        'accepted'             => ['bg'=>'#ede9fe','color'=>'#5b21b6','label'=>'مقبول مبدئياً'],
        'assigned'             => ['bg'=>'#e0f2fe','color'=>'#0369a1','label'=>'مُسند رسمياً'],
        'in_progress'          => ['bg'=>'#dbeafe','color'=>'#1e40af','label'=>'قيد التنفيذ ميدانياً'],
        'pending_confirmation' => ['bg'=>'#ecfdf5','color'=>'#065f46','label'=>'بانتظار تأكيد المستفيد'],
        'completed'            => ['bg'=>'#d1fae5','color'=>'#065f46','label'=>'مكتمل بنجاح'],
        'under_review'         => ['bg'=>'#fee2e2','color'=>'#991b1b','label'=>'تحت المراجعة'],
        'cancelled'            => ['bg'=>'#f3f4f6','color'=>'#6b7280','label'=>'ملغي'],
        'provider_apologized'  => ['bg'=>'#fce7f3','color'=>'#9d174d','label'=>'اعتذر مقدم الخدمة'],
        'provider_delayed'     => ['bg'=>'#ffedd5','color'=>'#c2410c','label'=>'تأخير مقدم الخدمة'],
        'no_provider_found'    => ['bg'=>'#f3f4f6','color'=>'#4b5563','label'=>'لم يُوجد مقدم خدمة'],
    ];
    $sc = $statusConfig[$serviceRequest->status] ?? ['bg'=>'#f3f4f6','color'=>'#6b7280','label'=>$serviceRequest->status];
@endphp

<div style="display:grid; grid-template-columns: 1fr 340px; gap:24px; align-items:start;">

    {{-- العمود الأيمن: تفاصيل الطلب والخط الزمني --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- بطاقة رأس الطلب والتفاصيل --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            <div style="background:linear-gradient(135deg, #354e20 0%, #4e6b35 100%); padding:24px;">
                <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                    <div>
                        <div style="font-size:12px; color:#dfe6d5; font-weight:800; margin-bottom:4px; direction:ltr; text-align:right;">
                            {{ $serviceRequest->public_id ?? '#'.$serviceRequest->id }}
                        </div>
                        <h2 style="margin:0; font-size:19px; font-weight:900; color:#fff;">{{ $serviceRequest->title }}</h2>
                        <div style="font-size:12px; color:#b8cfa0; margin-top:4px;">{{ $serviceRequest->service_type }}</div>
                    </div>
                    <span style="background:{{ $sc['bg'] }}; color:{{ $sc['color'] }}; padding:5px 16px; border-radius:12px; font-size:12px; font-weight:800; white-space:nowrap;">
                        {{ $sc['label'] }}
                    </span>
                </div>
            </div>

            <div style="padding:24px;">
                <div style="font-size:13px; color:#4b5563; line-height:1.7; background:#f9fafb; padding:16px; border-radius:12px; margin-bottom:20px;">
                    <strong style="color:#1a1f36; display:block; margin-bottom:4px;">وصف الطلب والاحتياج:</strong>
                    {{ $serviceRequest->description ?? 'لا يوجد وصف مضاف' }}
                </div>

                <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:14px;">
                    <div style="border:1px solid #e5e7eb; border-radius:12px; padding:12px 14px;">
                        <span style="font-size:11px; color:#9ca3af; font-weight:700; display:block; margin-bottom:4px;">الموعد المحدد</span>
                        <div style="font-size:13px; font-weight:800; color:#1a1f36;">
                            {{ $serviceRequest->scheduled_at ? $serviceRequest->scheduled_at->format('Y/m/d H:i') : 'غير محدد' }}
                        </div>
                    </div>

                    <div style="border:1px solid #e5e7eb; border-radius:12px; padding:12px 14px;">
                        <span style="font-size:11px; color:#9ca3af; font-weight:700; display:block; margin-bottom:4px;">الموقع الجغرافي</span>
                        <div style="font-size:13px; font-weight:800; color:#1a1f36;">{{ $serviceRequest->location ?? '—' }}</div>
                    </div>

                    <div style="border:1px solid #e5e7eb; border-radius:12px; padding:12px 14px;">
                        <span style="font-size:11px; color:#9ca3af; font-weight:700; display:block; margin-bottom:4px;">نوع التسعير / الأجر</span>
                        <div style="font-size:13px; font-weight:800; color:#1a1f36;">
                            {{ $serviceRequest->proposed_price > 0 ? number_format($serviceRequest->proposed_price) . ' ₪' : 'خدمة تطوعية مجانية' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- الخط الزمني لتغيرات حالة الطلب (Timeline) --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            <div style="padding:18px 24px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-timeline" style="color:#354e20; font-size:16px;"></i>
                <h3 style="margin:0; font-size:15px; font-weight:900; color:#1a1f36;">الخط الزمني لمراحل وحالة الطلب</h3>
            </div>

            <div style="padding:24px;">
                @if (count($timeline) > 0)
                <div style="position:relative; padding-right:24px;">
                    {{-- الخط العمودي الممتد --}}
                    <div style="position:absolute; right:11px; top:12px; bottom:12px; width:2px; background:#e5e7eb;"></div>

                    @foreach ($timeline as $step)
                    <div style="position:relative; margin-bottom:24px; display:flex; gap:16px; align-items:flex-start;">
                        {{-- أيقونة النقطة على الخط --}}
                        <div style="width:24px; height:24px; border-radius:50%; background:{{ $step['color'] }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:11px; z-index:1; flex-shrink:0; margin-top:2px; box-shadow:0 0 0 4px #fff;">
                            <i class="{{ $step['icon'] }}"></i>
                        </div>

                        {{-- محتوى النقطة الزمنية --}}
                        <div style="flex:1; background:#f9fafb; border:1px solid #f3f4f6; border-radius:12px; padding:12px 16px;">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                <span style="font-weight:800; font-size:13px; color:#1a1f36;">{{ $step['title'] }}</span>
                                <span style="font-size:11px; color:#6b7280; font-weight:600;">
                                    {{ $step['time']->format('Y/m/d - h:i A') }}
                                    <span style="color:#9ca3af;">({{ $step['time']->diffForHumans() }})</span>
                                </span>
                            </div>
                            <div style="font-size:12px; color:#4b5563; line-height:1.5;">
                                {{ $step['description'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div style="text-align:center; padding:24px; color:#9ca3af; font-size:12px;">
                    لا تتوفر سجلات زمنية لهذا الطلب
                </div>
                @endif
            </div>
        </div>

        {{-- الشكاوى المرتبطة إن وجدت --}}
        @if ($serviceRequest->complaints->isNotEmpty())
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e2dcd0;">
            <div style="padding:16px 20px; border-bottom:1px solid #f3f4f6; display:flex; align-items:center; gap:8px;">
                <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;"></i>
                <h3 style="margin:0; font-size:13px; font-weight:800; color:#1a1f36;">الشكاوى المسجلة على هذا الطلب ({{ $serviceRequest->complaints->count() }})</h3>
            </div>
            <div style="padding:8px 0;">
                @foreach ($serviceRequest->complaints as $c)
                <div style="padding:14px 20px; border-bottom:1px solid #f9fafb; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-weight:800; font-size:13px; color:#374151;">{{ $c->reporter?->name ?? 'مجهول' }}</div>
                        <div style="font-size:12px; color:#6b7280; margin-top:2px;">{{ $c->description }}</div>
                    </div>
                    <a href="{{ route('admin.complaints.show', $c) }}"
                        style="background:#e6edd9; color:#354e20; padding:6px 14px; border-radius:8px; font-size:11px; font-weight:700; text-decoration:none;">
                        معاينة الشكوى ←
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- العمود الأيسر: المستفيد والمتطوع والتدخل الإداري --}}
    <div style="display:flex; flex-direction:column; gap:20px;">

        {{-- بطاقة المستفيد --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); padding:20px; border:1px solid #e2dcd0;">
            <div style="font-size:11px; font-weight:800; color:#6b7280; margin-bottom:12px;">بيانات كبير السن (المستفيد)</div>
            @if ($serviceRequest->elderProfile?->user)
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                <div style="width:42px; height:42px; border-radius:50%; background:#83a55b; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:15px;">
                    {{ mb_substr($serviceRequest->elderProfile->user->name, 0, 1) }}
                </div>
                <div>
                    <div style="font-weight:800; font-size:14px; color:#1a1f36;">{{ $serviceRequest->elderProfile->user->name }}</div>
                    <div style="font-size:11px; color:#9ca3af;">{{ $serviceRequest->elderProfile->user->email }}</div>
                </div>
            </div>
            <div style="font-size:12px; color:#4b5563; margin-bottom:6px;">
                <strong>الهاتف:</strong> {{ $serviceRequest->elderProfile->phone_number ?? '—' }}
            </div>
            <div style="font-size:12px; color:#4b5563; margin-bottom:14px;">
                <strong>المدينة:</strong> {{ $serviceRequest->elderProfile->city ?? '—' }}
            </div>
            @else
            <p style="color:#9ca3af; font-size:12px; margin:0;">بيانات المستفيد غير متوفرة</p>
            @endif
        </div>

        {{-- بطاقة المتطوع --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); padding:20px; border:1px solid #e2dcd0;">
            <div style="font-size:11px; font-weight:800; color:#6b7280; margin-bottom:12px;">بيانات مقدم الخدمة (المتطوع)</div>
            @if ($serviceRequest->serviceProviderProfile?->user)
            <div style="display:flex; align-items:center; gap:12px; margin-bottom:12px;">
                <div style="width:42px; height:42px; border-radius:50%; background:#354e20; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:15px;">
                    {{ mb_substr($serviceRequest->serviceProviderProfile->user->name, 0, 1) }}
                </div>
                <div>
                    <div style="font-weight:800; font-size:14px; color:#1a1f36;">{{ $serviceRequest->serviceProviderProfile->user->name }}</div>
                    <div style="font-size:11px; color:#f59e0b; font-weight:700;">
                        ⭐ {{ number_format($serviceRequest->serviceProviderProfile->average_rating ?? 0, 1) }}
                        · المستوى {{ $serviceRequest->serviceProviderProfile->tier }}
                    </div>
                </div>
            </div>
            <div style="font-size:12px; color:#4b5563; margin-bottom:6px;">
                <strong>الهاتف:</strong> {{ $serviceRequest->serviceProviderProfile->phone_number ?? '—' }}
            </div>
            <div style="font-size:12px; color:#4b5563; margin-bottom:14px;">
                <strong>المهام المنجزة:</strong> {{ $serviceRequest->serviceProviderProfile->completed_tasks_count }}
            </div>
            @else
            <div style="text-align:center; padding:16px; background:#f9fafb; border-radius:10px; color:#9ca3af; font-size:12px;">
                لم يتم إسناد الطلب لمتطوع بعد
            </div>
            @endif
        </div>

        {{-- تدخل إداري --}}
        <div style="background:#fff; border-radius:16px; box-shadow:0 1px 6px rgba(0,0,0,0.06); padding:20px; border:1px solid #e2dcd0; border-top:4px solid #354e20;">
            <div style="font-size:13px; font-weight:800; color:#1a1f36; margin-bottom:10px;">
                <i class="fa-solid fa-pen-to-square" style="color:#354e20; margin-left:6px;"></i>
                تدخل إداري لتعديل الحالة
            </div>
            <form method="POST" action="{{ route('admin.requests.force-status', $serviceRequest) }}">
                @csrf
                <select name="status" style="width:100%; border:1px solid #e5e7eb; border-radius:10px; padding:9px 12px; font-size:12.5px; font-family:inherit; margin-bottom:12px;">
                    @foreach (\App\Models\ServiceRequest::STATUSES as $s)
                    @php
                        $labels = [
                            'pending_acceptance'   => 'في الانتظار',
                            'accepted'             => 'مقبول مبدئياً',
                            'assigned'             => 'مُسند للمتطوع',
                            'in_progress'          => 'قيد التنفيذ',
                            'pending_confirmation' => 'بانتظار التأكيد',
                            'completed'            => 'مكتمل',
                            'under_review'         => 'تحت المراجعة',
                            'cancelled'            => 'ملغي',
                            'provider_apologized'  => 'اعتذر المتطوع',
                            'provider_delayed'     => 'تأخير',
                            'no_provider_found'    => 'لم يُوجد متطوع',
                        ];
                    @endphp
                    <option value="{{ $s }}" {{ $serviceRequest->status === $s ? 'selected' : '' }}>
                        {{ $labels[$s] ?? $s }}
                    </option>
                    @endforeach
                </select>
                <button type="submit"
                    style="width:100%; background:#354e20; color:#fff; border:none; border-radius:10px; padding:10px; font-size:12.5px; font-weight:700; cursor:pointer; font-family:inherit;"
                    onclick="return confirm('هل أنت متأكد من تغيير حالة الطلب إدارياً؟')">
                    حفظ تغيير الحالة
                </button>
            </form>
        </div>

    </div>

</div>

</x-admin-layout>
