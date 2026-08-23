<x-guest-layout>
    <div class="text-center">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-[#eef2e8] text-3xl text-[#31421e] shadow-sm">
            <svg class="h-8 w-8 text-[#31421e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>
        <p class="text-xs font-bold text-[#718256]">منصة إحسان</p>
        <h1 class="mt-1 text-2xl font-extrabold text-[#31421e]">توثيق البريد الإلكتروني</h1>
        <p class="mt-3 text-sm leading-7 text-slate-600">
            شكرًا لتسجيلك في منصة إحسان! قبل البدء، يرجى تفعيل حسابك من خلال الضغط على الرابط الذي تم إرساله إلى بريدك الإلكتروني.
        </p>
    </div>

    @if (session('status') === 'verification-link-sent')
        <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-center text-sm font-semibold text-emerald-800" role="status">
            تم إرسال رابط توثيق جديد إلى عنوان بريدك الإلكتروني المسجل.
        </div>
    @endif

    <div class="mt-8 space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="w-full rounded-xl bg-[#31421e] px-5 py-3.5 font-bold text-white shadow-sm transition hover:bg-[#52643a] focus:outline-none focus:ring-2 focus:ring-[#718256] focus:ring-offset-2">
                إعادة إرسال رابط التوثيق
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full rounded-xl border-2 border-[#dfe6d5] px-5 py-3 text-sm font-bold text-slate-600 transition hover:bg-[#eef2e8] hover:text-[#31421e]">
                تسجيل الخروج
            </button>
        </form>
    </div>
</x-guest-layout>
