<x-guest-layout>
    <div class="text-center">
        <h1 class="text-2xl font-black text-[#31421e]">إنشاء كلمة مرور جديدة</h1>
        <p class="mt-3 text-sm leading-7 text-slate-500">اختر كلمة مرور قوية لحماية حسابك في إحسان.</p>
    </div>
    <form method="POST" action="{{ route('password.store') }}" class="mt-7 space-y-5">@csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div>
            <label for="email" class="mb-2 block text-sm font-bold">البريد الإلكتروني</label>
            <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required
                autofocus autocomplete="username"
                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <label for="password" class="mb-2 block text-sm font-bold">كلمة المرور الجديدة</label>
            <input id="password" name="password" type="password" minlength="8" required autocomplete="new-password"
                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <div>
            <label for="password_confirmation" class="mb-2 block text-sm font-bold">تأكيد كلمة المرور</label>
            <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" required
                autocomplete="new-password"
                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
        </div>
        <button class="w-full rounded-xl bg-[#31421e] px-5 py-3.5 font-bold text-white hover:bg-[#52643a]">حفظ كلمة
            المرور</button>
    </form>
</x-guest-layout>
