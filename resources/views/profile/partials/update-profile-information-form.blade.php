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

            {{-- TODO: reconnect in stage 3.2/3.3 --}}
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
