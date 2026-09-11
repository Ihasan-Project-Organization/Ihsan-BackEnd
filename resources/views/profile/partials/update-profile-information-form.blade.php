<section>
    <header class="flex items-center gap-3">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#e6edd9] text-lg text-[#354e20]">
            👤
        </div>
        <div>
            <h2 class="text-xl font-black text-[#31421e]">الملف الشخصي</h2>
            <p class="text-xs text-slate-500">تعديل معلوماتك الشخصية وصورتك ورقم هاتفك المعتمد.</p>
        </div>
    </header>

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        {{-- حقل الصورة الشخصية --}}
        <div>
            <label class="mb-2 block text-xs font-bold text-slate-700">الصورة الشخصية (اختيارية)</label>
            <div class="flex items-center gap-4">
                @if ($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-2xl object-cover border-2 border-[#dfe6d5] shadow-sm">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[#eef2e8] text-xl font-black text-[#31421e] border border-slate-200">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                @endif
                <div class="flex-1">
                    <input id="profile_picture" name="profile_picture" type="file" accept="image/*"
                        class="block w-full text-xs text-slate-500 file:mr-0 file:ml-3 file:rounded-xl file:border-0 file:bg-[#eef2e8] file:px-4 file:py-2 file:text-xs file:font-bold file:text-[#31421e] hover:file:bg-[#dfe6d5] cursor-pointer">
                    <p class="mt-1 text-[11px] text-slate-400">صيغ الصور المدعومة: JPG, PNG (بحد أقصى 2MB)</p>
                    <x-input-error class="mt-1" :messages="$errors->get('profile_picture')" />
                </div>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="name" class="mb-1.5 block text-xs font-bold text-slate-700">الاسم الكامل</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name"
                    class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#718256] focus:ring-[#718256]">
                <x-input-error class="mt-1" :messages="$errors->get('name')" />
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-xs font-bold text-slate-700">البريد الإلكتروني</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                    class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#718256] focus:ring-[#718256]">
                <x-input-error class="mt-1" :messages="$errors->get('email')" />
            </div>
        </div>

        <div>
            <label for="phone_number" class="mb-1.5 block text-xs font-bold text-slate-700">رقم الهاتف للتواصل</label>
            <input id="phone_number" name="phone_number" type="tel" 
                value="{{ old('phone_number', $user->phone_number) }}" 
                placeholder="05XXXXXXXX"
                class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#718256] focus:ring-[#718256]" dir="ltr">
            <x-input-error class="mt-1" :messages="$errors->get('phone_number')" />
            <p class="mt-1 text-[11px] text-slate-400">يستخدم للتنسيق بين المستفيد ومقدم الخدمة بعد التوكيل الرسمي للطلب.</p>
        </div>

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
            <div class="rounded-xl bg-amber-50 border border-amber-200 p-3 text-xs text-amber-800 flex items-center justify-between">
                <span>البريد الإلكتروني غير موثق حتى الآن.</span>
                <button form="send-verification" class="font-bold underline text-amber-900 hover:text-amber-700 cursor-pointer">
                    إعادة إرسال رابط التوثيق
                </button>
            </div>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="rounded-xl bg-[#31421e] px-7 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                حفظ التغييرات
            </button>
            @if (session('status') === 'profile-updated')
                <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                    <span>✓ تم حفظ التغييرات بنجاح</span>
                </span>
            @endif
        </div>
    </form>
</section>
