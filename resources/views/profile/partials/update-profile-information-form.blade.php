<section>
    <header>
        <h2 class="text-xl font-black text-[#31421e]">المعلومات الشخصية</h2>
        <p class="mt-2 text-sm leading-7 text-slate-500">حدّث معلومات حسابك وبيانات التسجيل.</p>
    </header>
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>
    <form method="POST" action="{{ route('profile.update') }}" class="mt-7">
        @csrf
        @method('patch')
        @php($profile = $user->registrationProfile)
        <div class="grid gap-5 sm:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-sm font-bold">الاسم الكامل</label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name"
                    class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>
            <div>
                <label for="email" class="mb-2 block text-sm font-bold">البريد الإلكتروني</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                    autocomplete="username"
                    class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
            <div>
                <label for="dob" class="mb-2 block text-sm font-bold">تاريخ الميلاد</label>
                <input id="dob" name="dob" type="date"
                    value="{{ old('dob', $profile?->date_of_birth?->format('Y-m-d')) }}" required
                    class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                <x-input-error class="mt-2" :messages="$errors->get('dob')" />
            </div>
            <div>
                <label for="phone" class="mb-2 block text-sm font-bold">رقم الجوال</label>
                <input id="phone" name="phone" value="{{ old('phone', $profile?->phone) }}" required
                    class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            @if ($user->account_type === 'volunteer')
                <div class="sm:col-span-2">
                    <label for="id_number" class="mb-2 block text-sm font-bold">رقم الهوية</label>
                    <input id="id_number" name="id_number" value="{{ old('id_number', $profile?->identity_number) }}"
                        required
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                    <x-input-error class="mt-2" :messages="$errors->get('id_number')" />
                </div>
            @else
                <div>
                    <label for="city" class="mb-2 block text-sm font-bold">المدينة</label>
                    <input id="city" name="city" value="{{ old('city', $profile?->city) }}" required
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                    <x-input-error class="mt-2" :messages="$errors->get('city')" />
                </div>
                <div>
                    <label for="housing_type" class="mb-2 block text-sm font-bold">نوع السكن</label>
                    <select id="housing_type" name="housing_type" required
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                        <option value="apartment" @selected(old('housing_type', $profile?->housing_type) === 'apartment')>شقة</option>
                        <option value="house" @selected(old('housing_type', $profile?->housing_type) === 'house')>منزل مستقل</option>
                        <option value="family" @selected(old('housing_type', $profile?->housing_type) === 'family')>سكن مع العائلة</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label for="address" class="mb-2 block text-sm font-bold">العنوان التفصيلي</label>
                    <textarea id="address" name="address" required rows="3"
                        class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">{{ old('address', $profile?->address) }}</textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('address')" />
                </div>
            @endif

            <div class="sm:col-span-2">
                <label for="extra_info" class="mb-2 block text-sm font-bold">معلومات إضافية</label>
                <textarea id="extra_info" name="extra_info" rows="3"
                    class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">{{ old('extra_info', $profile?->extra_info) }}</textarea>
            </div>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <p class="mt-5 rounded-xl bg-amber-50 p-4 text-sm text-amber-800">البريد غير موثّق. <button
                    form="send-verification" class="font-bold underline">إعادة إرسال رابط التوثيق</button>
            </p>
        @endif

        <div class="mt-7 flex items-center gap-4">
            <button class="rounded-xl bg-[#31421e] px-7 py-3 font-bold text-white hover:bg-[#52643a]">حفظ
                التغييرات</button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2500)"
                    class="text-sm font-bold text-emerald-700">تم الحفظ بنجاح</p>
            @endif
        </div>
    </form>
</section>
