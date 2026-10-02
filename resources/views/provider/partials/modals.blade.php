{{-- Modal 1: إنهاء الخدمة وإرسال ملخص التنفيذ --}}
<div id="finishServiceModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl sm:p-8 animate-fadeIn">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-800">
                        <x-app-icon name="check" class="w-5 h-5 text-emerald-700" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-[#31421e]">إكمال وإنهاء الخدمة</h3>
                        <p class="text-xs text-slate-500">إشعار كبير السن باكتمال الخدمة وبانتظار تأكيده</p>
                    </div>
                </div>
                <button type="button" onclick="closeFinishServiceModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">
                    <x-app-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>

            <form id="finishServiceForm" method="POST" action="" class="mt-5 space-y-4">
                @csrf

                <div class="rounded-2xl bg-[#f8faf6] p-4 border border-[#dfe6d5]">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#31421e]">
                        <x-app-icon name="information-circle" class="w-4 h-4 text-[#52643a] shrink-0" />
                        <span>ملاحظة تشغيلية</span>
                    </div>
                    <p class="mt-1 text-xs leading-5 text-slate-600">
                        إنهاء الخدمة سينقل الطلب إلى حالة "بانتظار التأكيد"، ويتم إغلاق الطلب رسمياً واحتسابه في سجلك فور تأكيد كبير السن.
                    </p>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-2xl bg-[#31421e] py-3 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                        تأكيد إكمال الخدمة
                    </button>
                    <button type="button" onclick="closeFinishServiceModal()" class="rounded-2xl border border-slate-200 px-5 py-3 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 2: الإبلاغ عن تأخير متوقع --}}
<div id="reportDelayModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl sm:p-8 animate-fadeIn">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-800">
                        <x-app-icon name="clock" class="w-5 h-5 text-amber-700" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">الإبلاغ عن تأخير متوقع</h3>
                        <p class="text-xs text-slate-500">إشعار كبير السن بالموعد الجديد المتوقع للوصول</p>
                    </div>
                </div>
                <button type="button" onclick="closeReportDelayModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">
                    <x-app-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>

            <form id="reportDelayForm" method="POST" action="" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">المدة المتوقعة للتأخير</label>
                    <select name="delay_minutes" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-4 py-2.5 text-xs font-bold text-slate-800 focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20">
                        <option value="15">15 دقيقة</option>
                        <option value="30" selected>30 دقيقة</option>
                        <option value="45">45 دقيقة</option>
                        <option value="60">ساعة كاملة</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">سبب التأخير التقديري</label>
                    <textarea name="delay_reason" required rows="2" placeholder="مثال: ازدحام مروري، طارئ في الطريق..."
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs focus:border-[#52643a] focus:bg-white focus:ring-2 focus:ring-[#52643a]/20"></textarea>
                </div>

                <div class="rounded-2xl bg-amber-50 p-3.5 border border-amber-200 text-xs text-amber-900 leading-5">
                    <p class="font-bold mb-1 flex items-center gap-1.5">
                        <x-app-icon name="information-circle" class="w-4 h-4 text-amber-700 shrink-0" />
                        <span>الضوابط التشغيلية للتأخير:</span>
                    </p>
                    <ul class="list-disc list-inside text-[11px] text-amber-800 space-y-0.5">
                        <li>يتم إشعار كبير السن فوراً بالموعد الجديد لانتظارك.</li>
                        <li>يُتاح للمستفيد حرية الانتظار أو طلب البحث عن متطوع بديل.</li>
                    </ul>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-2xl bg-amber-600 py-3 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-amber-700 transition cursor-pointer">
                        إرسال إشعار التأخير
                    </button>
                    <button type="button" onclick="closeReportDelayModal()" class="rounded-2xl border border-slate-200 px-5 py-3 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 3: الاعتذار عن الطلب --}}
<div id="apologizeModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl sm:p-8 animate-fadeIn">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-800">
                        <x-app-icon name="exclamation-triangle" class="w-5 h-5 text-rose-700" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">الاعتذار عن المهمة</h3>
                        <p class="text-xs text-slate-500">سيُعاد نشر الطلب فوراً للبحث عن متطوع بديل لكبير السن</p>
                    </div>
                </div>
                <button type="button" onclick="closeApologizeModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">
                    <x-app-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>

            <form id="apologizeForm" method="POST" action="" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">سبب الاعتذار</label>
                    <textarea name="apology_reason" required rows="3" placeholder="يرجى توضيح سبب عدم تمكنك من تنفيذ المهمة..."
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-200"></textarea>
                </div>

                <div class="rounded-2xl bg-rose-50 p-3.5 border border-rose-200 text-xs text-rose-900 leading-5">
                    <p class="font-bold mb-1 flex items-center gap-1.5">
                        <x-app-icon name="exclamation-triangle" class="w-4 h-4 text-rose-600 shrink-0" />
                        <span>ضوابط الاعتذار المعتمدة:</span>
                    </p>
                    <ul class="list-disc list-inside text-[11px] text-rose-800 space-y-0.5">
                        <li>يؤدي الاعتذار إلى فصل إسنادك عن الطلب فوراً وإشعار المستفيد لإعادة الجدولة.</li>
                        <li>يُسجل الاعتذار كحادثة عدم موثوقية في سجل الحساب لدى الإدارة.</li>
                        <li>تكرار 3 حوادث خلال 30 يوماً يرسل تنبيهاً للإدارة لمراجعة الحساب.</li>
                    </ul>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-2xl bg-rose-600 py-3 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-rose-700 transition cursor-pointer">
                        تأكيد الاعتذار وفصل الإسناد
                    </button>
                    <button type="button" onclick="closeApologizeModal()" class="rounded-2xl border border-slate-200 px-5 py-3 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        رجوع
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal 4: تفاصيل الطلب المتاح --}}
<div id="requestDetailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl sm:p-8 animate-fadeIn">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div id="detailIcon" class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef2e8] text-[#31421e] shadow-sm">
                        <x-app-icon name="handshake" class="w-6 h-6" />
                    </div>
                    <div>
                        <span id="detailId" class="text-xs font-mono font-bold text-slate-400">#REQ-1000</span>
                        <h3 id="detailTitle" class="text-lg font-black text-[#31421e]">تفاصيل الطلب</h3>
                    </div>
                </div>
                <button type="button" onclick="closeDetailsModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">
                    <x-app-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>

            <div class="mt-5 space-y-4">
                <div class="rounded-2xl bg-slate-50 p-4 border border-slate-100 text-xs">
                    <h4 class="font-bold text-slate-400 mb-1">وصف الطلب والاحتياج المطلوب:</h4>
                    <p id="detailDescription" class="leading-relaxed text-slate-700"></p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 text-xs">
                    <div class="rounded-2xl bg-[#f8faf6] p-3.5 border border-[#dfe6d5]">
                        <span class="font-bold text-slate-400 block">الموقع التقريبي:</span>
                        <p id="detailLocation" class="font-bold text-slate-800 mt-0.5"></p>
                    </div>
                    <div class="rounded-2xl bg-[#f8faf6] p-3.5 border border-[#dfe6d5]">
                        <span class="font-bold text-slate-400 block">الموعد المحدد:</span>
                        <p id="detailSchedule" class="font-bold text-slate-800 mt-0.5"></p>
                    </div>
                </div>

                <div id="detailPrivacyNotice" class="rounded-2xl bg-amber-50 p-3.5 border border-amber-200 flex items-center gap-2 text-xs text-amber-800">
                    <x-app-icon name="lock-closed" class="w-4 h-4 text-amber-700 shrink-0" />
                    <span>يظهر العنوان الدقيق ورقم هاتف المستفيد في قائمة "طلباتي" فور قبول الطلب وتوكيله.</span>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3 border-t border-slate-100 pt-4">
                <button type="button" onclick="closeDetailsModal()" class="rounded-2xl border border-slate-200 px-6 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    إغلاق
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal 5: بلاغ خاص للإدارة --}}
<div id="reportIssueModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl sm:p-8 animate-fadeIn">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-700">
                        <x-app-icon name="flag" class="w-5 h-5 text-rose-700" />
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">إبلاغ عن مشكلة للإدارة</h3>
                        <p class="text-xs text-slate-500">هذا بلاغ سري للمراجعة، وليس تقييماً للمستفيد.</p>
                    </div>
                </div>
                <button type="button" onclick="closeReportIssueModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">
                    <x-app-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>

            <form id="reportIssueForm" method="POST" action="" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">نوع المشكلة</label>
                    <select name="issue_type" required class="w-full rounded-2xl border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-800 focus:border-[#718256] focus:bg-white focus:ring-2 focus:ring-[#718256]/20">
                        <option value="contact">تعذر التواصل</option>
                        <option value="safety">ملاحظة تتعلق بالسلامة</option>
                        <option value="conduct">مشكلة في التعامل</option>
                        <option value="information">معلومات الطلب غير مطابقة</option>
                        <option value="other">ملاحظة أخرى</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">تفاصيل إضافية <span class="text-slate-400">(اختياري، ومطلوب عند اختيار ملاحظة أخرى)</span></label>
                    <textarea name="description" rows="3"
                        placeholder="اكتب ما حدث باختصار لتتمكن الإدارة من المتابعة..."
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs focus:border-[#718256] focus:bg-white focus:ring-2 focus:ring-[#718256]/20"></textarea>
                </div>

                <div class="rounded-2xl bg-slate-50 p-3 text-[11px] text-slate-500 leading-5 border border-slate-200 flex items-center gap-2">
                    <x-app-icon name="lock-closed" class="w-4 h-4 text-slate-400 shrink-0" />
                    <span>البلاغ يصل للإدارة فقط، ولا يُنقص نقاط المستفيد ولا يظهر في ملفه أو أمام مقدم خدمة آخر.</span>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-2xl bg-rose-700 py-3 text-xs font-bold text-white shadow-md hover:bg-rose-800 transition cursor-pointer">
                        إرسال البلاغ للإدارة
                    </button>
                    <button type="button" onclick="closeReportIssueModal()" class="rounded-2xl border border-slate-200 px-5 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        إلغاء
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openFinishServiceModal(actionUrl) {
        resetModalSubmitBtn('finishServiceForm');
        document.getElementById('finishServiceForm').action = actionUrl;
        document.getElementById('finishServiceModal').classList.remove('hidden');
    }
    function closeFinishServiceModal() {
        resetModalSubmitBtn('finishServiceForm');
        document.getElementById('finishServiceModal').classList.add('hidden');
    }

    function openReportDelayModal(actionUrl) {
        resetModalSubmitBtn('reportDelayForm');
        document.getElementById('reportDelayForm').action = actionUrl;
        document.getElementById('reportDelayModal').classList.remove('hidden');
    }
    function closeReportDelayModal() {
        resetModalSubmitBtn('reportDelayForm');
        document.getElementById('reportDelayModal').classList.add('hidden');
    }

    function openApologizeModal(actionUrl) {
        resetModalSubmitBtn('apologizeForm');
        document.getElementById('apologizeForm').action = actionUrl;
        document.getElementById('apologizeModal').classList.remove('hidden');
    }
    function closeApologizeModal() {
        resetModalSubmitBtn('apologizeForm');
        document.getElementById('apologizeModal').classList.add('hidden');
    }

    function openReportIssueModal(actionUrl) {
        document.getElementById('reportIssueForm').action = actionUrl;
        document.getElementById('reportIssueModal').classList.remove('hidden');
    }
    function closeReportIssueModal() {
        document.getElementById('reportIssueModal').classList.add('hidden');
    }

    const modalServiceIcons = {
        'shopping-cart': '<svg class="w-6 h-6 text-[#31421e]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>',
        'walking': '<svg class="w-6 h-6 text-[#31421e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M13 4a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" /><path d="M7 21l3 -4" /><path d="M16 21l-2 -4l-3 -3l1 -6" /><path d="M6 12l2 -3l4 -1l3 3l3 1" /></svg>',
        'pill': '<svg class="w-6 h-6 text-[#31421e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z"/><path stroke-linecap="round" stroke-linejoin="round" d="m8.5 8.5 7 7"/></svg>',
        'broom': '<svg class="w-6 h-6 text-[#31421e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 11l5 5l-1.5 1.5l-5 -5" /><path d="M12 13l-4 4l-1 -1l4 -4" /><path d="M3 21l6.5 -6.5" /><path d="M15 9l4 -4" /><path d="M18 6l2 2" /></svg>',
        'handshake': '<svg class="w-6 h-6 text-[#31421e]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="m11 17 2 2a1 1 0 0 0 1.4 0l4.3-4.3a1 1 0 0 0 0-1.4l-2-2"/><path stroke-linecap="round" stroke-linejoin="round" d="m13 7.5-2-2a1 1 0 0 0-1.4 0L5.3 9.8a1 1 0 0 0 0 1.4l2 2"/><path stroke-linecap="round" stroke-linejoin="round" d="m14 14 2.5 2.5a1 1 0 0 0 1.4 0l2.8-2.8a1 1 0 0 0 0-1.4l-3.3-3.3"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.5 9.5 7 7a1 1 0 0 0-1.4 0L2.8 9.8a1 1 0 0 0 0 1.4l3.3 3.3"/></svg>',
        'heart': '<svg class="w-6 h-6 text-[#31421e]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>'
    };

    function openDetailsModal(id, title, desc, loc, sched, icon) {
        document.getElementById('detailId').innerText = id;
        document.getElementById('detailTitle').innerText = title;
        document.getElementById('detailDescription').innerText = desc;
        document.getElementById('detailLocation').innerText = loc;
        document.getElementById('detailSchedule').innerText = sched;
        if (icon && modalServiceIcons[icon]) {
            document.getElementById('detailIcon').innerHTML = modalServiceIcons[icon];
        } else if (icon && icon.includes('<svg')) {
            document.getElementById('detailIcon').innerHTML = icon;
        }
        document.getElementById('requestDetailsModal').classList.remove('hidden');
    }
    function closeDetailsModal() {
        document.getElementById('requestDetailsModal').classList.add('hidden');
    }

    function resetModalSubmitBtn(formId) {
        var form = document.getElementById(formId);
        if (form) {
            var submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && submitBtn.disabled) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                if (submitBtn.dataset.originalText) {
                    submitBtn.innerHTML = submitBtn.dataset.originalText;
                }
            }
        }
    }

    // منع النقر المزدوج وتعطيل زر الإرسال فور الضغط عليه
    document.addEventListener('DOMContentLoaded', function() {
        ['finishServiceForm', 'reportDelayForm', 'apologizeForm'].forEach(function(formId) {
            var form = document.getElementById(formId);
            if (form) {
                form.addEventListener('submit', function(e) {
                    var submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        if (submitBtn.disabled) {
                            e.preventDefault();
                            return false;
                        }
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        submitBtn.dataset.originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> جاري المعالجة...';
                    }
                });
            }
        });
    });
</script>
