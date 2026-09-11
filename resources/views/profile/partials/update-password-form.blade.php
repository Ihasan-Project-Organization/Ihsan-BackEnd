<section>
    <header class="flex items-center gap-3">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-[#e6edd9] text-lg text-[#354e20]">
            🔒
        </div>
        <div>
            <h2 class="text-xl font-black text-[#31421e]">كلمة المرور والأمان</h2>
            <p class="text-xs text-slate-500">استخدم كلمة مرور قوية لا تقل عن ثمانية أحرف لضمان حماية حسابك.</p>
        </div>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="mb-1.5 block text-xs font-bold text-slate-700">كلمة المرور الحالية</label>
            <input id="current_password" name="current_password" type="password" autocomplete="current-password"
                class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#718256] focus:ring-[#718256]">
            <x-input-error class="mt-1" :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="new_password" class="mb-1.5 block text-xs font-bold text-slate-700">كلمة المرور الجديدة</label>
                <input id="new_password" name="password" type="password" autocomplete="new-password"
                    class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#718256] focus:ring-[#718256]">
                <x-input-error class="mt-1" :messages="$errors->updatePassword->get('password')" />
            </div>

            <div>
                <label for="new_password_confirmation" class="mb-1.5 block text-xs font-bold text-slate-700">تأكيد كلمة المرور</label>
                <input id="new_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                    class="w-full rounded-xl border-slate-300 px-4 py-2.5 text-sm focus:border-[#718256] focus:ring-[#718256]">
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="rounded-xl bg-[#31421e] px-7 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-[#52643a] transition cursor-pointer">
                تحديث كلمة المرور
            </button>
            @if (session('status') === 'password-updated')
                <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-bold text-emerald-700 flex items-center gap-1">
                    <span>✓ تم تحديث كلمة المرور بنجاح</span>
                </span>
            @endif
        </div>
    </form>
</section>
