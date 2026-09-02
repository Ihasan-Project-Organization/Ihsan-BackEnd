{{-- Modal 1: إنهاء الخدمة وإرسال ملخص التنفيذ --}}
<div id="finishServiceModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl sm:p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-800 text-lg">✓</div>
                    <div>
                        <h3 class="text-xl font-black text-[#31421e]">إنهاء الخدمة</h3>
                        <p class="text-xs text-slate-500">إرسال ملخص التنفيذ إلى كبير السن للتأكيد</p>
                    </div>
                </div>
                <button type="button" onclick="closeFinishServiceModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">✕</button>
            </div>

            <form id="finishServiceForm" method="POST" action="" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ملخص التنفيذ والملاحظات (اختياري)</label>
                    <textarea name="completion_notes" rows="3" placeholder="اكتب ملخصاً لما تم تنفيذه (مثل: تم شراء كافة الأغراض المطلوبة وتسليم الإيصال...)"
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20"></textarea>
                </div>

                <div class="rounded-2xl bg-[#f8faf6] p-4 border border-[#dfe6d5]">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#31421e]">
                        <span>ℹ️</span>
                        <span>ملاحظة هامة</span>
                    </div>
                    <p class="mt-1 text-xs leading-5 text-slate-600">
                        إنهاء الخدمة من طرفك سينقل الطلب إلى حالة "بانتظار التأكيد"، وسيتم إغلاق الطلب وتحديث سجلك بمجرد تأكيد كبير السن.
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-2xl bg-[#31421e] py-3 text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                        تأكيد إكمال الخدمة
                    </button>
                    <button type="button" onclick="closeFinishServiceModal()" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 2: الإبلاغ عن تأخير --}}
<div id="reportDelayModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl sm:p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-800 text-lg">⏳</div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800">الإبلاغ عن تأخير متوقع</h3>
                        <p class="text-xs text-slate-500">إشعار كبير السن بالموعد الجديد المتوقع للوصول</p>
                    </div>
                </div>
                <button type="button" onclick="closeReportDelayModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">✕</button>
            </div>

            <form id="reportDelayForm" method="POST" action="" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">المدة المتوقعة للتأخير</label>
                    <select name="delay_minutes" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                        <option value="10">10 دقائق (دون تأثير على الالتزام)</option>
                        <option value="20" selected>20 دقيقة (خصم نقطتين من مؤشر الالتزام)</option>
                        <option value="30">30 دقيقة (خصم نقطتين من مؤشر الالتزام)</option>
                        <option value="45">45 دقيقة (خصم 5 نقاط من مؤشر الالتزام)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">سبب التأخير</label>
                    <textarea name="delay_reason" required rows="2" placeholder="اذكر سبب التأخير (ازدحام مروري، طارئ في الطريق...)"
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20"></textarea>
                </div>

                <div class="rounded-2xl bg-amber-50 p-4 border border-amber-200">
                    <p class="text-xs font-bold text-amber-900">قواعد التأخير المعتمدة:</p>
                    <ul class="mt-1 list-disc list-inside text-xs text-amber-800 space-y-1">
                        <li>أقل من 15 دقيقة: دون خصم.</li>
                        <li>من 15 إلى 30 دقيقة: خصم نقطتين من مؤشر الالتزام.</li>
                        <li>سيتاح لكبير السن مهلة انتظار 30 دقيقة مع عداد مباشر أو طلب بديل.</li>
                    </ul>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-2xl bg-amber-600 py-3 text-sm font-bold text-white shadow-md hover:bg-amber-700 transition cursor-pointer">
                        إرسال إشعار التأخير
                    </button>
                    <button type="button" onclick="closeReportDelayModal()" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 3: الاعتذار عن الطلب والبحث عن بديل --}}
<div id="apologizeModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl sm:p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-800 text-lg">⚠️</div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800">الاعتذار عن الطلب</h3>
                        <p class="text-xs text-slate-500">سيُعاد نشر الطلب فوراً للبحث عن متطوع بديل</p>
                    </div>
                </div>
                <button type="button" onclick="closeApologizeModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">✕</button>
            </div>

            <form id="apologizeForm" method="POST" action="" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">سبب الاعتذار</label>
                    <textarea name="apology_reason" required rows="3" placeholder="يرجى توضيح سبب الاعتذار..."
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-200"></textarea>
                </div>

                <div class="rounded-2xl bg-rose-50 p-4 border border-rose-200">
                    <p class="text-xs font-bold text-rose-900">تأثير الاعتذار على مؤشر الالتزام:</p>
                    <ul class="mt-1 list-disc list-inside text-xs text-rose-800 space-y-1">
                        <li>قبل أكثر من 24 ساعة: خصم نقطتين (-2).</li>
                        <li>خلال 24 ساعة: خصم 5 نقاط (-5).</li>
                        <li>قبل ساعتين أو أثناء التنفيذ: خصم 10 نقاط (-10).</li>
                        <li>في حال وجود ظرف طارئ موثق، يمكنك مراجعة الإدارة لإلغاء الخصم.</li>
                    </ul>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-2xl bg-rose-600 py-3 text-sm font-bold text-white shadow-md hover:bg-rose-700 transition cursor-pointer">
                        تأكيد الاعتذار وفصل الإسناد
                    </button>
                    <button type="button" onclick="closeApologizeModal()" class="rounded-2xl border border-slate-200 px-5 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        رجوع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 4: تفاصيل الطلب الكاملة --}}
<div id="requestDetailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl sm:p-8">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div id="detailIcon" class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef2e8] text-2xl">🤝</div>
                    <div>
                        <span id="detailId" class="text-xs font-bold text-slate-400">#REQ-1048</span>
                        <h3 id="detailTitle" class="text-xl font-black text-[#31421e]">شراء أغراض منزلية</h3>
                    </div>
                </div>
                <button type="button" onclick="closeDetailsModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">✕</button>
            </div>

            <div class="mt-6 space-y-4">
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                    <h4 class="text-xs font-bold text-slate-400">وصف الطلب والاحتياج</h4>
                    <p id="detailDescription" class="mt-1 text-sm leading-6 text-slate-700"></p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400">الموقع والمنطقة</span>
                        <p id="detailLocation" class="mt-1 text-sm font-bold text-slate-800"></p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100">
                        <span class="text-xs font-bold text-slate-400">الموعد المحدد</span>
                        <p id="detailSchedule" class="mt-1 text-sm font-bold text-slate-800"></p>
                    </div>
                </div>

                <div id="detailPrivacyNotice" class="rounded-2xl bg-amber-50 p-3.5 border border-amber-200 flex items-center gap-2 text-xs text-amber-800">
                    <span>🔒</span>
                    <span>يظهر العنوان الدقيق ورقم التواصل بعد قبول الطلب ونقله إلى قائمة طلباتي.</span>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeDetailsModal()" class="rounded-2xl border border-slate-200 px-6 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openFinishServiceModal(actionUrl) {
        document.getElementById('finishServiceForm').action = actionUrl;
        document.getElementById('finishServiceModal').classList.remove('hidden');
    }
    function closeFinishServiceModal() {
        document.getElementById('finishServiceModal').classList.add('hidden');
    }

    function openReportDelayModal(actionUrl) {
        document.getElementById('reportDelayForm').action = actionUrl;
        document.getElementById('reportDelayModal').classList.remove('hidden');
    }
    function closeReportDelayModal() {
        document.getElementById('reportDelayModal').classList.add('hidden');
    }

    function openApologizeModal(actionUrl) {
        document.getElementById('apologizeForm').action = actionUrl;
        document.getElementById('apologizeModal').classList.remove('hidden');
    }
    function closeApologizeModal() {
        document.getElementById('apologizeModal').classList.add('hidden');
    }

    function openDetailsModal(id, title, desc, loc, sched, icon) {
        document.getElementById('detailId').innerText = id;
        document.getElementById('detailTitle').innerText = title;
        document.getElementById('detailDescription').innerText = desc;
        document.getElementById('detailLocation').innerText = loc;
        document.getElementById('detailSchedule').innerText = sched;
        if(icon) document.getElementById('detailIcon').innerText = icon;
        document.getElementById('requestDetailsModal').classList.remove('hidden');
    }
    function closeDetailsModal() {
        document.getElementById('requestDetailsModal').classList.add('hidden');
    }
</script>

