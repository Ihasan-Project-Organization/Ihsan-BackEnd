<script>
window.openRescheduleModal = function(id, publicId, url) {
    window.dispatchEvent(new CustomEvent('open-reschedule-modal', { detail: { id: id, publicId: publicId, url: url } }));
};
window.openEditModal = function(id, title, description, location, scheduledAt, url) {
    window.dispatchEvent(new CustomEvent('open-edit-modal', { detail: { id: id, title: title, description: description, location: location, scheduledAt: scheduledAt, url: url } }));
};
window.openReviewModal = function(id, providerName, url) {
    window.dispatchEvent(new CustomEvent('open-review-modal', { detail: { id: id, providerName: providerName, url: url } }));
};
window.openCancelModal = function(id, publicId, url) {
    window.dispatchEvent(new CustomEvent('open-cancel-modal', { detail: { id: id, publicId: publicId, url: url } }));
};
window.openContactModal = function(name, phone) {
    window.dispatchEvent(new CustomEvent('open-contact-modal', { detail: { name: name, phone: phone } }));
};
</script>

{{-- 1. مودال تحديد موعد جديد وإعادة النشر (Reschedule Modal) --}}
<div x-data="{
    open: false,
    requestId: null,
    publicId: '',
    actionUrl: '',
    scheduledAt: '',
    init() {
        window.addEventListener('open-reschedule-modal', (e) => {
            this.requestId = e.detail.id;
            this.publicId = e.detail.publicId;
            this.actionUrl = e.detail.url;
            this.scheduledAt = '';
            this.open = true;
        });
    }
}"
x-show="open"
x-cloak
class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">

    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>

    <div class="relative z-10 w-full max-w-sm overflow-hidden rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-2xl text-center my-auto">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef2e8] text-[#31421e]">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </div>

        <h3 class="mt-3 text-lg font-extrabold text-[#31421e]">تحديد موعد جديد</h3>
        <p class="mt-1 text-xs text-slate-500">اختر موعد تنفيذ جديدًا لإعادة نشر الطلب <span class="font-bold text-[#31421e]" x-text="publicId"></span></p>

        <form method="POST" :action="actionUrl" class="mt-5 text-right">
            @csrf
            @method('patch')
            <div>
                <label class="block text-xs font-bold text-slate-700">موعد تنفيذ الخدمة</label>
                <input type="datetime-local" name="scheduled_at" x-model="scheduledAt" required
                    class="mt-1.5 w-full rounded-xl border-slate-300 px-3.5 py-2.5 text-xs sm:text-sm focus:border-[#718256] focus:ring-[#718256]">
            </div>

            <div class="mt-5 flex gap-2.5">
                <button type="submit"
                    class="flex-1 rounded-xl bg-[#31421e] py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                    إعادة نشر الطلب
                </button>
                <button type="button" @click="open = false"
                    class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    تراجع
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 2. مودال تعديل الطلب وإعادة النشر (Edit & Reschedule Modal) --}}
<div x-data="{
    open: false,
    requestId: null,
    actionUrl: '',
    title: '',
    description: '',
    location: '',
    scheduledAt: '',
    init() {
        window.addEventListener('open-edit-modal', (e) => {
            this.requestId = e.detail.id;
            this.actionUrl = e.detail.url;
            this.title = e.detail.title;
            this.description = e.detail.description;
            this.location = e.detail.location;
            this.scheduledAt = e.detail.scheduledAt;
            this.open = true;
        });
    }
}"
x-show="open"
x-cloak
class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">

    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>

    <div class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-2xl my-auto">
        <div class="flex items-center justify-between pb-2 border-b border-slate-100 mb-4">
            <h3 class="text-base sm:text-lg font-extrabold text-[#31421e]">تعديل بيانات الطلب</h3>
            <button type="button" @click="open = false" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form method="POST" :action="actionUrl" class="space-y-3">
            @csrf
            @method('patch')
            <div>
                <label class="block text-xs font-bold text-slate-700">نوع الخدمة / العنوان</label>
                <input type="text" name="title" x-model="title" required
                    class="mt-1 w-full rounded-xl border-slate-300 px-3.5 py-2 text-xs sm:text-sm focus:border-[#718256] focus:ring-[#718256]">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700">الشرح والتفاصيل</label>
                <textarea name="description" x-model="description" rows="2.5" required
                    class="mt-1 w-full rounded-xl border-slate-300 px-3.5 py-2 text-xs sm:text-sm leading-5 focus:border-[#718256] focus:ring-[#718256]"></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div>
                    <label class="block text-xs font-bold text-slate-700">موعد التنفيذ</label>
                    <input type="datetime-local" name="scheduled_at" x-model="scheduledAt" required
                        class="mt-1 w-full rounded-xl border-slate-300 px-3 py-2 text-xs focus:border-[#718256] focus:ring-[#718256]">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700">الموقع</label>
                    <input type="text" name="location" x-model="location" required
                        class="mt-1 w-full rounded-xl border-slate-300 px-3 py-2 text-xs focus:border-[#718256] focus:ring-[#718256]">
                </div>
            </div>

            <div class="mt-5 flex gap-2.5 pt-2">
                <button type="submit"
                    class="flex-1 rounded-xl bg-[#31421e] py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                    حفظ وإعادة النشر
                </button>
                <button type="button" @click="open = false"
                    class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 3. مودال تقييم الخدمة (Review Modal) --}}
<div x-data="{
    open: false,
    requestId: null,
    actionUrl: '',
    providerName: '',
    rating: 5,
    comment: '',
    init() {
        window.addEventListener('open-review-modal', (e) => {
            this.requestId = e.detail.id;
            this.actionUrl = e.detail.url;
            this.providerName = e.detail.providerName;
            this.rating = 5;
            this.comment = '';
            this.open = true;
        });
    }
}"
x-show="open"
x-cloak
class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">

    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>

    <div class="relative z-10 w-full max-w-sm overflow-hidden rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-2xl text-center my-auto">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-500">
            <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
        </div>

        <h3 class="mt-3 text-lg font-extrabold text-[#31421e]">تقييم الخدمة</h3>
        <p class="mt-1 text-xs text-slate-500">كيف كانت تجربتك مع <span class="font-bold text-[#31421e]" x-text="providerName"></span>؟</p>

        <form method="POST" :action="actionUrl" class="mt-4">
            @csrf
            <div class="flex justify-center gap-2 mb-3">
                <template x-for="star in [1, 2, 3, 4, 5]" :key="star">
                    <button type="button" @click="rating = star" class="text-2xl transition hover:scale-125 focus:outline-none"
                        :class="star <= rating ? 'text-amber-400' : 'text-slate-200'">
                        ★
                    </button>
                </template>
                <input type="hidden" name="rating" :value="rating">
            </div>

            <textarea name="comment" x-model="comment" rows="2.5"
                placeholder="اكتب ملاحظاتك لمقدم الخدمة (اختياري)..."
                class="w-full rounded-xl border-slate-300 p-2.5 text-xs leading-5 focus:border-[#718256] focus:ring-[#718256]"></textarea>

            <div class="mt-4 flex gap-2.5">
                <button type="submit"
                    class="flex-1 rounded-xl bg-[#31421e] py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition cursor-pointer">
                    إرسال التقييم
                </button>
                <button type="button" @click="open = false"
                    class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    إلغاء
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 4. مودال إلغاء الطلب (Cancel Modal) --}}
<div x-data="{
    open: false,
    requestId: null,
    actionUrl: '',
    publicId: '',
    reason: 'لم تعد الخدمة مطلوبة',
    init() {
        window.addEventListener('open-cancel-modal', (e) => {
            this.requestId = e.detail.id;
            this.publicId = e.detail.publicId;
            this.actionUrl = e.detail.url;
            this.open = true;
        });
    }
}"
x-show="open"
x-cloak
class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">

    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>

    <div class="relative z-10 w-full max-w-sm overflow-hidden rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-2xl text-center my-auto">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-red-50 text-red-600">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>

        <h3 class="mt-3 text-base sm:text-lg font-extrabold text-slate-800">إلغاء الطلب؟</h3>
        <p class="mt-1 text-xs text-slate-500">سيتم نقل الطلب <span class="font-bold text-slate-800" x-text="publicId"></span> إلى قسم الطلبات الملغاة.</p>

        <form method="POST" :action="actionUrl" class="mt-4 text-right">
            @csrf
            @method('delete')
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">سبب الإلغاء</label>
                <select name="cancellation_reason" x-model="reason" class="w-full rounded-xl border-slate-300 text-xs py-2 px-3 focus:border-[#718256] focus:ring-[#718256]">
                    <option value="لم تعد الخدمة مطلوبة">لم تعد الخدمة مطلوبة</option>
                    <option value="تمت المساعدة من طرف آخر">تمت المساعدة من طرف آخر</option>
                    <option value="تغيير في الخطة أو الموعد">تغيير في الخطة أو الموعد</option>
                    <option value="سبب آخر">سبب آخر</option>
                </select>
            </div>

            <div class="mt-5 flex gap-2.5">
                <button type="submit"
                    class="flex-1 rounded-xl bg-red-600 py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-red-700 transition cursor-pointer">
                    نعم، إلغاء الطلب
                </button>
                <button type="button" @click="open = false"
                    class="rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm font-bold text-slate-600 hover:bg-slate-50 transition cursor-pointer">
                    تراجع
                </button>
            </div>
        </form>
    </div>
</div>

{{-- 5. مودال التواصل مع مقدم الخدمة لكبير السن (Contact Provider Modal) --}}
<div x-data="{
    open: false,
    name: '',
    phone: '',
    init() {
        window.addEventListener('open-contact-modal', (e) => {
            this.name = e.detail.name;
            this.phone = e.detail.phone;
            this.open = true;
        });
    }
}"
x-show="open"
x-cloak
class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">

    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="open = false"></div>

    <div class="relative z-10 w-full max-w-sm rounded-2xl sm:rounded-3xl bg-white p-5 sm:p-6 shadow-2xl text-center my-auto">
        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-[#eef2e8] text-[#31421e]">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
        </div>
        <h3 class="mt-3 text-lg font-black text-[#31421e]">تواصل مع مقدم الخدمة</h3>
        <p class="mt-1 text-xs text-slate-500" x-text="name"></p>

        <div class="mt-5 space-y-2.5">
            <a :href="'tel:' + phone"
                class="flex items-center justify-center gap-2 w-full rounded-xl bg-[#31421e] py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-[#52643a] transition">
                <span>📞 اتصال هاتفي مباشر</span>
                <span dir="ltr" class="font-mono text-xs text-[#dfe6d5]" x-text="phone"></span>
            </a>

            <a :href="'https://wa.me/' + phone.replace(/[^0-9]/g, '')" target="_blank"
                class="flex items-center justify-center gap-2 w-full rounded-xl bg-emerald-600 py-2.5 text-xs sm:text-sm font-bold text-white shadow-md hover:bg-emerald-700 transition">
                <span>💬 محادثة واتساب</span>
            </a>
        </div>

        <button type="button" @click="open = false"
            class="mt-4 w-full rounded-xl border border-slate-200 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50">
            إغلاق
        </button>
    </div>
</div>
