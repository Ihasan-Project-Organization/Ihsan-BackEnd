<x-guest-layout>
    <div class="text-center">
        <h1 class="text-2xl font-black text-[#31421e]">توثيق البريد الإلكتروني</h1>
        <p class="mt-3 text-sm leading-7 text-slate-500">أرسلنا رابط التوثيق إلى بريدك. افتح الرسالة واضغط على الرابط
            لإكمال التوثيق.</p>
    </div>
    @if (session('status') === 'verification-link-sent')
        <div class="mt-5 rounded-xl bg-emerald-50 p-4 text-sm font-bold text-emerald-800">تم إرسال رابط توثيق جديد.</div>
    @endif
    <div class="mt-7 grid gap-3 sm:grid-cols-2">
        <form method="POST" action="{{ route('verification.send') }}">@csrf
            <button class="w-full rounded-xl bg-[#31421e] px-5 py-3 font-bold text-white hover:bg-[#52643a]">إعادة
                إرسال
                الرابط</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button
                class="w-full rounded-xl border-2 border-[#718256] px-5 py-3 font-bold text-[#52643a] hover:bg-[#eef2e8]">تسجيل
                الخروج</button>
        </form>
    </div>
</x-guest-layout>
