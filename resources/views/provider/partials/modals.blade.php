{{-- Modal 1: إنهاء الخدمة وإرسال ملخص التنفيذ --}}
<div id="finishServiceModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-3xl bg-white p-6 shadow-2xl sm:p-8 animate-fadeIn">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-800 text-lg font-black">✓</div>
                    <div>
                        <h3 class="text-lg font-black text-[#31421e]">إكمال وإنهاء الخدمة</h3>
                        <p class="text-xs text-slate-500">إشعار كبير السن باكتمال الخدمة وبانتظار تأكيده</p>
                    </div>
                </div>
                <button type="button" onclick="closeFinishServiceModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">✕</button>
            </div>

            <form id="finishServiceForm" method="POST" action="" class="mt-5 space-y-4">
                @csrf

                <div class="rounded-2xl bg-[#f8faf6] p-4 border border-[#dfe6d5]">
                    <div class="flex items-center gap-2 text-xs font-bold text-[#31421e]">
                        <span>ℹ️</span>
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
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-800 text-lg">⏳</div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">الإبلاغ عن تأخير متوقع</h3>
                        <p class="text-xs text-slate-500">إشعار كبير السن بالموعد الجديد المتوقع للوصول</p>
                    </div>
                </div>
                <button type="button" onclick="closeReportDelayModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">✕</button>
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
                    <p class="font-bold mb-1">📌 الضوابط التشغيلية للتأخير:</p>
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
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-rose-100 text-rose-800 text-lg">⚠️</div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">الاعتذار عن المهمة</h3>
                        <p class="text-xs text-slate-500">سيُعاد نشر الطلب فوراً للبحث عن متطوع بديل لكبير السن</p>
                    </div>
                </div>
                <button type="button" onclick="closeApologizeModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">✕</button>
            </div>

            <form id="apologizeForm" method="POST" action="" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">سبب الاعتذار</label>
                    <textarea name="apology_reason" required rows="3" placeholder="يرجى توضيح سبب عدم تمكنك من تنفيذ المهمة..."
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-200"></textarea>
                </div>

                <div class="rounded-2xl bg-rose-50 p-3.5 border border-rose-200 text-xs text-rose-900 leading-5">
                    <p class="font-bold mb-1">⚠️ ضوابط الاعتذار المعتمدة:</p>
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
                    <div id="detailIcon" class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef2e8] text-2xl shadow-sm">🤝</div>
                    <div>
                        <span id="detailId" class="text-xs font-mono font-bold text-slate-400">#REQ-1000</span>
                        <h3 id="detailTitle" class="text-lg font-black text-[#31421e]">تفاصيل الطلب</h3>
                    </div>
                </div>
                <button type="button" onclick="closeDetailsModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">✕</button>
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
                    <span class="text-base">🔒</span>
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

{{-- Modal 5: تقييم كبير السن (اختياري، خاص بالإدارة) --}}
<div id="rateElderModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-modal="true" role="dialog">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl sm:p-8 animate-fadeIn">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-800 text-lg">⭐</div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">تقييم المستفيد</h3>
                        <p class="text-xs text-slate-500">تقييم داخلي للإدارة لضمان سلامة بيئة العمل</p>
                    </div>
                </div>
                <button type="button" onclick="closeRateElderModal()" class="rounded-xl p-2 text-slate-400 hover:bg-slate-100 transition cursor-pointer">✕</button>
            </div>

            <form id="rateElderForm" method="POST" action="" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">التقييم العام للتعامل</label>
                    <select name="stars" class="w-full rounded-2xl border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-800 focus:border-[#718256] focus:bg-white focus:ring-2 focus:ring-[#718256]/20">
                        <option value="5">⭐⭐⭐⭐⭐ ممتاز وسلس جداً</option>
                        <option value="4">⭐⭐⭐⭐ جيد جداً</option>
                        <option value="3">⭐⭐⭐ متوسط</option>
                        <option value="2">⭐⭐ واجهت بعض الصعوبات</option>
                        <option value="1">⭐ تجربة صعبة</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظاتك للإدارة (اختياري)</label>
                    <textarea name="comment" rows="3"
                        placeholder="أي ملاحظات تود مشاركتها مع إدارة المنصة بشأن بيئة الخدمة..."
                        class="w-full rounded-2xl border-slate-200 bg-slate-50 px-3.5 py-2.5 text-xs focus:border-[#718256] focus:bg-white focus:ring-2 focus:ring-[#718256]/20"></textarea>
                </div>

                <div class="rounded-2xl bg-slate-50 p-3 text-[11px] text-slate-500 leading-5 border border-slate-200">
                    🔒 هذا التقييم اختياري ومحفوظ للإدارة حصراً ولا يظهر لكبير السن أو في ملفه العام.
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="flex-1 rounded-2xl bg-[#31421e] py-3 text-xs font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                        إرسال التقييم
                    </button>
                    <button type="button" onclick="closeRateElderModal()" class="rounded-2xl border border-slate-200 px-5 py-3 text-xs font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                        إلغاء
                    </button>
                </div>
            </form>
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

    function openRateElderModal(actionUrl) {
        document.getElementById('rateElderForm').action = actionUrl;
        document.getElementById('rateElderModal').classList.remove('hidden');
    }
    function closeRateElderModal() {
        document.getElementById('rateElderModal').classList.add('hidden');
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
