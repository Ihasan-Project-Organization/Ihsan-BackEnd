<x-guest-layout>
    <div class="text-center">
        <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#eef2e8] text-2xl">✉</span>
        <h1 class="mt-5 text-2xl font-black text-[#31421e]">استعادة كلمة المرور</h1>
        <p class="mt-3 text-sm leading-7 text-slate-500">أدخل بريدك الإلكتروني وسنرسل إليك رابطًا آمنًا لإنشاء كلمة مرور
            جديدة.</p>
    </div>
    <x-auth-session-status class="mt-5 rounded-xl bg-emerald-50 p-4 text-sm text-emerald-800" :status="session('status')" />
    <form method="POST" action="{{ route('password.email') }}" class="mt-7">@csrf
        <label for="email" class="mb-2 block text-sm font-bold">البريد الإلكتروني</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
            autocomplete="email" placeholder="name@example.com"
            class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
        <button class="mt-6 w-full rounded-xl bg-[#31421e] px-5 py-3.5 font-bold text-white hover:bg-[#52643a]">إرسال
            رابط
            الاستعادة</button>
    </form>
    <a href="{{ route('login') }}"
        class="mt-6 block text-center text-sm font-bold text-[#52643a] hover:underline">العودة إلى تسجيل الدخول</a>
</x-guest-layout>
