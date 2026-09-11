<x-admin-layout>

<div style="max-width:880px; margin:0 auto;">

    {{-- رأس الصفحة --}}
    <div style="margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:900; color:#1a1f36;">إعدادات النظام العامة وعتبات الترقية (Tiers)</h2>
        <p style="margin:4px 0 0; font-size:12px; color:#6b7280;">
            ضبط المعايير الرقمية لترقية وتخفيض مستويات مقدمي الخدمة (Tier System) في منصة إحسان.
        </p>
    </div>

    @if (session('success'))
        <div style="background:#ecfdf5; border:1px solid #a7f3d0; border-radius:12px; padding:14px 18px; margin-bottom:20px; display:flex; align-items:center; gap:10px; color:#065f46; font-size:13px; font-weight:700;">
            <i class="fa-solid fa-circle-check" style="font-size:16px;"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:14px 18px; margin-bottom:20px;">
            <div style="font-weight:800; color:#991b1b; font-size:13px; margin-bottom:6px; display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>يرجى تصحيح أخطاء الإدخال التالية:</span>
            </div>
            <ul style="margin:0; padding-right:20px; color:#b91c1c; font-size:12px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- بطاقة توضيحية لآلية المستويات --}}
    <div style="background:linear-gradient(135deg, #354e20, #253915); border-radius:16px; padding:22px 24px; color:#fff; margin-bottom:24px; box-shadow:0 4px 14px rgba(53,78,32,0.2);">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:12px;">
            <i class="fa-solid fa-circle-info" style="color:#f4a400; font-size:18px;"></i>
            <h3 style="margin:0; font-size:15px; font-weight:800; color:#fff;">كيف يعمل نظام المستويات (Tier System) في المنصة؟</h3>
        </div>
        <p style="margin:0 0 14px; font-size:12px; color:#dfe6d5; line-height:1.6;">
            يتم تقييم وترقية مقدم الخدمة آلياً بعد كل مهمة مكتملة بناءً على عدد المهام المنجزة ومتوسط التقييم العام. في حال تراجع التقييم عن العتبة المحددة، يُعاد تقييم المستوى تلقائياً.
        </p>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:12px;">
            <div style="background:rgba(255,255,255,0.08); border-radius:10px; padding:10px 14px;">
                <span style="font-weight:800; font-size:12px; color:#b8cfa0; display:block;">المستوى 1 (Tier 1)</span>
                <span style="font-size:11px; color:#f0f5ea;">مستوى الانضمام الافتراضي لكافة المتطوعين الجدد.</span>
            </div>
            <div style="background:rgba(255,255,255,0.08); border-radius:10px; padding:10px 14px;">
                <span style="font-weight:800; font-size:12px; color:#fde68a; display:block;">المستوى 2 (Tier 2)</span>
                <span style="font-size:11px; color:#f0f5ea;">متطوع معتمد ذو خبرة وموثوقية عالية في تقديم الخدمات.</span>
            </div>
            <div style="background:rgba(255,255,255,0.08); border-radius:10px; padding:10px 14px;">
                <span style="font-weight:800; font-size:12px; color:#fbcfe8; display:block;">المستوى 3 (Tier 3)</span>
                <span style="font-size:11px; color:#f0f5ea;">متطوع متميز ذو أولوية وأعلى جودة تقييم وخدمة.</span>
            </div>
        </div>
    </div>

    {{-- نموذج تعديل العتبات --}}
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        <div style="background:#fff; border-radius:18px; box-shadow:0 1px 8px rgba(0,0,0,0.06); padding:26px 28px; margin-bottom:20px; border:1px solid #e2dcd0;">
            
            {{-- القسم 1: عتبات المستوى الثاني --}}
            <div style="margin-bottom:28px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px; padding-bottom:10px; border-bottom:2px solid #e6edd9;">
                    <div style="width:28px; height:28px; border-radius:8px; background:#354e20; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:13px;">
                        2
                    </div>
                    <div>
                        <h4 style="margin:0; font-size:15px; font-weight:800; color:#1a1f36;">معايير الترقية إلى المستوى الثاني (Tier 2)</h4>
                        <span style="font-size:11px; color:#6b7280;">شروط انتقال المتطوع من المستوى الأول إلى المستوى الثاني.</span>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div>
                        <label style="display:block; font-size:12px; font-weight:800; color:#374151; margin-bottom:6px;">
                            الحد الأدنى لعدد المهام المكتملة
                        </label>
                        <div style="position:relative;">
                            <input type="number" name="tier_2_tasks_threshold" min="1" step="1"
                                value="{{ old('tier_2_tasks_threshold', $settings['tier_2_tasks_threshold']) }}" required
                                style="width:100%; border:1px solid #d1d5db; border-radius:10px; padding:10px 14px; font-size:13px; font-family:inherit; box-sizing:border-box;">
                        </div>
                        <span style="font-size:10px; color:#6b7280; display:block; margin-top:4px;">القيمة المعتمدة الافتراضية: 10 مهام.</span>
                    </div>

                    <div>
                        <label style="display:block; font-size:12px; font-weight:800; color:#374151; margin-bottom:6px;">
                            الحد الأدنى لمتوسط التقييم (من 5.0)
                        </label>
                        <div style="position:relative;">
                            <input type="number" name="tier_2_rating_threshold" min="1.0" max="5.0" step="0.05"
                                value="{{ old('tier_2_rating_threshold', $settings['tier_2_rating_threshold']) }}" required
                                style="width:100%; border:1px solid #d1d5db; border-radius:10px; padding:10px 14px; font-size:13px; font-family:inherit; box-sizing:border-box;">
                        </div>
                        <span style="font-size:10px; color:#6b7280; display:block; margin-top:4px;">القيمة المعتمدة الافتراضية: 4.0 نجوم.</span>
                    </div>
                </div>
            </div>

            {{-- القسم 2: عتبات المستوى الثالث --}}
            <div style="margin-bottom:10px;">
                <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px; padding-bottom:10px; border-bottom:2px solid #fef3c7;">
                    <div style="width:28px; height:28px; border-radius:8px; background:#f59e0b; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:900; font-size:13px;">
                        3
                    </div>
                    <div>
                        <h4 style="margin:0; font-size:15px; font-weight:800; color:#1a1f36;">معايير الترقية إلى المستوى الثالث المتميز (Tier 3)</h4>
                        <span style="font-size:11px; color:#6b7280;">شروط انتقال المتطوع إلى أعلى مستوى تميز وخدمة في النظام.</span>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div>
                        <label style="display:block; font-size:12px; font-weight:800; color:#374151; margin-bottom:6px;">
                            الحد الأدنى لعدد المهام المكتملة
                        </label>
                        <div style="position:relative;">
                            <input type="number" name="tier_3_tasks_threshold" min="2" step="1"
                                value="{{ old('tier_3_tasks_threshold', $settings['tier_3_tasks_threshold']) }}" required
                                style="width:100%; border:1px solid #d1d5db; border-radius:10px; padding:10px 14px; font-size:13px; font-family:inherit; box-sizing:border-box;">
                        </div>
                        <span style="font-size:10px; color:#6b7280; display:block; margin-top:4px;">القيمة المعتمدة الافتراضية: 30 مهمة (يجب أن تكون أكبر من Tier 2).</span>
                    </div>

                    <div>
                        <label style="display:block; font-size:12px; font-weight:800; color:#374151; margin-bottom:6px;">
                            الحد الأدنى لمتوسط التقييم (من 5.0)
                        </label>
                        <div style="position:relative;">
                            <input type="number" name="tier_3_rating_threshold" min="1.0" max="5.0" step="0.05"
                                value="{{ old('tier_3_rating_threshold', $settings['tier_3_rating_threshold']) }}" required
                                style="width:100%; border:1px solid #d1d5db; border-radius:10px; padding:10px 14px; font-size:13px; font-family:inherit; box-sizing:border-box;">
                        </div>
                        <span style="font-size:10px; color:#6b7280; display:block; margin-top:4px;">القيمة المعتمدة الافتراضية: 4.3 نجوم.</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- زر الحفظ والتأكيد --}}
        <div style="display:flex; justify-content:flex-start; gap:12px;">
            <button type="submit"
                style="background:#354e20; color:#fff; border:none; border-radius:12px; padding:12px 32px; font-size:14px; font-weight:800; cursor:pointer; font-family:inherit; display:inline-flex; align-items:center; gap:8px; box-shadow:0 4px 12px rgba(53,78,32,0.25); transition:0.2s;"
                onmouseover="this.style.transform='translateY(-1px)';"
                onmouseout="this.style.transform='translateY(0)';">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>حفظ التعديلات في النظام</span>
            </button>
        </div>
    </form>

</div>

</x-admin-layout>
