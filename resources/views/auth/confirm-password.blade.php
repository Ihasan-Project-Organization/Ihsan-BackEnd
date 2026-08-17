<x-guest-layout>
    <div class="text-center">
        <h1 class="text-2xl font-black text-[#31421e]">تأكيد كلمة المرور</h1>
        <p class="mt-3 text-sm leading-7 text-slate-500">هذه منطقة آمنة. أكد كلمة مرورك قبل المتابعة.</p>
    </div>
    <form method="POST" action="{{ route('password.confirm') }}" class="mt-7">@csrf
        <label for="password" class="mb-2 block text-sm font-bold">كلمة المرور</label>
        <input id="password" name="password" type="password" required autocomplete="current-password"
            class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
        <button class="mt-6 w-full rounded-xl bg-[#31421e] px-5 py-3.5 font-bold text-white hover:bg-[#52643a]">تأكيد
            والمتابعة</button>
    </form>
</x-guest-layout>
