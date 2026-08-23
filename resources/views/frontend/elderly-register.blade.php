<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>تسجيل كبير السن | إحسان</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen w-full overflow-x-hidden bg-[#f3eee5] font-sans text-slate-800 antialiased">
    <main class="mx-auto min-h-screen w-full min-w-0 max-w-7xl overflow-x-hidden bg-white shadow-xl sm:my-6 sm:min-h-0 sm:w-[calc(100%-3rem)] sm:rounded-3xl">
        <header class="grid w-full min-w-0 overflow-hidden bg-[#eef2e8] lg:grid-cols-[280px_minmax(0,1fr)] sm:rounded-t-3xl">
            <img src="{{ asset('assets/img/oldage.jpeg') }}" alt="كبير سن"
                class="block h-40 w-full object-cover sm:h-48 lg:h-52">
            <div class="flex w-full min-w-0 flex-col items-start gap-4 px-4 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-10 sm:py-7">
                <div class="w-full min-w-0 sm:w-auto">
                    <p class="text-sm font-bold text-[#718256]">منصة إحسان</p>
                    <h1 class="max-w-full break-words mt-1 text-xl font-extrabold leading-[1.7] text-[#31421e] sm:text-3xl">إنشاء حساب كبير السن</h1>
                    <p class="mt-2 max-w-full break-words text-sm leading-7 text-slate-600">أدخل معلوماتك خلال ثلاث خطوات بسيطة.</p>
                </div>
                <a href="{{ route('frontend.login') }}"
                    class="min-h-11 shrink-0 rounded-xl border border-[#718256] px-4 py-2.5 text-sm font-bold text-[#52643a] hover:bg-white sm:px-5">الرجوع</a>
            </div>
        </header>
        <div class="w-full min-w-0 px-3 py-6 sm:px-10 sm:py-10">
            <ol class="mx-auto mb-7 grid max-w-3xl grid-cols-3 gap-1.5 sm:mb-9 sm:gap-2" aria-label="مراحل التسجيل">
                <li
                    class="step-indicator rounded-lg bg-[#31421e] px-1 py-3 text-center text-[10px] font-bold leading-5 text-white sm:rounded-xl sm:px-2 sm:text-sm">
                    1. المعلومات والسكن</li>
                <li
                    class="step-indicator rounded-lg bg-slate-100 px-1 py-3 text-center text-[10px] font-bold leading-5 text-slate-500 sm:rounded-xl sm:px-2 sm:text-sm">
                    2. المرفقات (اختياري)</li>
                <li
                    class="step-indicator rounded-lg bg-slate-100 px-1 py-3 text-center text-[10px] font-bold leading-5 text-slate-500 sm:rounded-xl sm:px-2 sm:text-sm">
                    3. المراجعة</li>
            </ol>

            @if ($errors->any())
                <div class="mx-auto mb-6 max-w-4xl rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="registrationForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="mx-auto w-full min-w-0 max-w-4xl">

                @csrf
                <input type="hidden" name="account_type" value="elderly">

                {{-- المرحلة الأولى: المعلومات والسكن --}}
                <section class="form-step" data-step="1">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-bold">الاسم بالكامل</label>
                            <input id="name" name="name" value="{{ old('name') }}" required
                                placeholder="مثال: أحمد محمد علي"
                                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                        </div>
                        <div>
                            <label for="dob" class="mb-2 block text-sm font-bold">تاريخ الميلاد</label>
                            <input id="dob" name="dob" type="date" value="{{ old('dob') }}" required
                                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                        </div>
                        <div>
                            <label for="phone" class="mb-2 block text-sm font-bold">رقم الجوال</label>
                            <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" required
                                placeholder="05XXXXXXXX"
                                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                        </div>
                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold">البريد الإلكتروني</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                placeholder="name@example.com"
                                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                        </div>
                        <div>
                            <label for="city" class="mb-2 block text-sm font-bold">المدينة / المنطقة</label>
                            <input id="city" name="city" value="{{ old('city') }}" required
                                placeholder="مثال: الرياض / حي النرجس"
                                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                        </div>
                        <div>
                            <label for="address" class="mb-2 block text-sm font-bold">العنوان التفصيلي</label>
                            <input id="address" name="address" value="{{ old('address') }}" required
                                placeholder="الشارع، رقم المبنى، المعلم القريب"
                                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                        </div>
                        <div class="md:col-span-2">
                            <label for="housing_type" class="mb-2 block text-sm font-bold">نوع السكن</label>
                            <select id="housing_type" name="housing_type" required
                                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                                <option value="">اختر نوع السكن</option>
                                <option value="apartment" {{ old('housing_type') === 'apartment' ? 'selected' : '' }}>شقة</option>
                                <option value="house" {{ old('housing_type') === 'house' ? 'selected' : '' }}>منزل مستقل</option>
                                <option value="family" {{ old('housing_type') === 'family' ? 'selected' : '' }}>سكن مع العائلة</option>
                            </select>
                        </div>
                        <div>
                            <label for="password" class="mb-2 block text-sm font-bold">كلمة المرور</label>
                            <input id="password" name="password" type="password" minlength="8" required
                                autocomplete="new-password" placeholder="••••••••"
                                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-bold">تأكيد كلمة المرور</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                minlength="8" required autocomplete="new-password" placeholder="••••••••"
                                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">
                        </div>
                        <div class="md:col-span-2">
                            <label for="extra_info" class="mb-2 block text-sm font-bold">معلومات إضافية أو ملاحظات صحية (اختياري)</label>
                            <textarea id="extra_info" name="extra_info" rows="2"
                                placeholder="أي تفاصيل ترغب بمشاركتها لمساعدتنا في تقديم رعاية أفضل..."
                                class="w-full rounded-xl border-slate-300 px-4 py-3 focus:border-[#718256] focus:ring-[#718256]">{{ old('extra_info') }}</textarea>
                        </div>
                    </div>
                </section>

                {{-- المرحلة الثانية: المرفقات والصور (اختياري) --}}
                <section class="form-step hidden" data-step="2">
                    <div class="mb-6 rounded-2xl bg-[#eef2e8] p-5 text-[#31421e]">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#31421e] text-lg text-white">ℹ</span>
                            <div>
                                <h3 class="font-extrabold">خطوة اختيارية</h3>
                                <p class="mt-1 text-xs leading-6 text-slate-600 sm:text-sm">
                                    يمكنك تخطي هذه الخطوة والضغط على "التالي" مباشرة، أو إرفاق صورة الهوية وصورتك الشخصية لتسهيل التحقق والتعرف.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-2">
                        <label
                            class="group flex min-h-52 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-6 text-center transition hover:border-[#718256] hover:bg-[#eef2e8]">
                            <span class="rounded-full bg-slate-200 px-3 py-1 text-[11px] font-bold text-slate-600 group-hover:bg-[#dfe6d5] group-hover:text-[#31421e]">اختياري</span>
                            <span class="mt-3 text-lg font-extrabold text-[#31421e]">صورة الهوية الشخصية</span>
                            <span class="mt-1 text-xs text-slate-500">PNG أو JPG أو PDF (حد أقصى 5MB)</span>
                            <input id="id_document" name="id_document" type="file"
                                accept="image/png,image/jpeg,application/pdf"
                                class="mt-4 block w-full max-w-xs text-sm file:ml-3 file:rounded-lg file:border-0 file:bg-[#31421e] file:px-4 file:py-2 file:font-bold file:text-white">
                        </label>

                        <label
                            class="group flex min-h-52 cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-6 text-center transition hover:border-[#718256] hover:bg-[#eef2e8]">
                            <span class="rounded-full bg-slate-200 px-3 py-1 text-[11px] font-bold text-slate-600 group-hover:bg-[#dfe6d5] group-hover:text-[#31421e]">اختياري</span>
                            <span class="mt-3 text-lg font-extrabold text-[#31421e]">الصورة الشخصية</span>
                            <span class="mt-1 text-xs text-slate-500">PNG أو JPG (حد أقصى 5MB)</span>
                            <input id="profile_photo" name="profile_photo" type="file"
                                accept="image/png,image/jpeg"
                                class="mt-4 block w-full max-w-xs text-sm file:ml-3 file:rounded-lg file:border-0 file:bg-[#31421e] file:px-4 file:py-2 file:font-bold file:text-white">
                        </label>
                    </div>

                    <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-xs leading-6 text-slate-600">
                        🔒 تُعامل جميع الوثائق والصور بسرية وأمان تام وفق أعلى معايير الخصوصية.
                    </div>
                </section>

                {{-- المرحلة الثالثة: المراجعة --}}
                <section class="form-step hidden" data-step="3">
                    <h2 class="mb-5 text-xl font-extrabold text-[#31421e]">راجع بياناتك قبل إنشاء الحساب</h2>
                    <div id="reviewGrid" class="grid gap-3 sm:grid-cols-2">
                    </div>
                    <label class="mt-6 flex items-start gap-3 rounded-xl bg-[#eef2e8] p-4 text-sm">
                        <input type="checkbox" required
                            class="mt-1 rounded border-slate-300 text-[#31421e] focus:ring-[#718256]">أوافق على الشروط
                        والأحكام وسياسة الخصوصية.</label>
                </section>

                <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-center">
                    <button id="prevButton" type="button"
                        class="hidden rounded-xl border-2 border-[#31421e] px-8 py-3 font-bold text-[#31421e] hover:bg-[#eef2e8]">السابق</button>
                    <button id="nextButton" type="button"
                        class="rounded-xl bg-[#31421e] px-10 py-3 font-bold text-white shadow-lg hover:bg-[#52643a]">التالي</button>
                </div>
            </form>
        </div>
    </main>
    <script>
        let currentStep = 1;
        const form = document.getElementById('registrationForm'),
            steps = [...document.querySelectorAll('.form-step')],
            indicators = [...document.querySelectorAll('.step-indicator')],
            prev = document.getElementById('prevButton'),
            next = document.getElementById('nextButton'),
            passwordInput = document.getElementById('password'),
            confirmationInput = document.getElementById('password_confirmation');

        function fields(step) {
            return [...document.querySelectorAll(`.form-step[data-step="${step}"] [required]`)]
        }

        function valid() {
            const list = fields(currentStep);
            for (const field of list) {
                if (!field.reportValidity()) return false
            }
            if (currentStep === 1 && passwordInput.value !== confirmationInput.value) {
                confirmationInput.setCustomValidity('كلمتا المرور غير متطابقتين');
                confirmationInput.reportValidity();
                return false
            }
            confirmationInput.setCustomValidity('');
            return true
        }

        function show() {
            steps.forEach((el, i) => el.classList.toggle('hidden', i + 1 !== currentStep));
            indicators.forEach((el, i) => {
                const active = i + 1 <= currentStep;
                el.classList.toggle('bg-[#31421e]', active);
                el.classList.toggle('text-white', active);
                el.classList.toggle('bg-slate-100', !active);
                el.classList.toggle('text-slate-500', !active)
            });
            prev.classList.toggle('hidden', currentStep === 1);
            next.textContent = currentStep === 3 ? 'إنشاء الحساب' : 'التالي'
        }

        function value(id) {
            return document.getElementById(id)
        }

        function review() {
            const type = value('housing_type');
            const idDocFile = value('id_document').files[0]?.name;
            const profilePhotoFile = value('profile_photo').files[0]?.name;

            const data = [
                ['الاسم بالكامل', value('name').value],
                ['تاريخ الميلاد', value('dob').value],
                ['رقم الجوال', value('phone').value],
                ['البريد الإلكتروني', value('email').value],
                ['المدينة / المنطقة', value('city').value],
                ['العنوان التفصيلي', value('address').value],
                ['نوع السكن', type.options[type.selectedIndex]?.text || 'غير محدد'],
                ['معلومات إضافية', value('extra_info').value || 'لا يوجد'],
                ['صورة الهوية الشخصية', idDocFile || 'لم يتم إرفاق ملف (اختياري)'],
                ['الصورة الشخصية', profilePhotoFile || 'لم يتم إرفاق صورة (اختياري)']
            ];
            document.getElementById('reviewGrid').innerHTML = data.map(([k, v]) => `<div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
<span class="block text-xs font-bold text-slate-500">${k}</span>
<span class="mt-1 block font-bold text-slate-800">${escapeHtml(v)}</span>
</div>`).join('')
        }

        function escapeHtml(v) {
            const e = document.createElement('div');
            e.textContent = v;
            return e.innerHTML
        }
        next.addEventListener('click', () => {
            if (!valid()) return;
            if (currentStep < 3) {
                currentStep++;
                if (currentStep === 3) review();
                show()
            } else form.requestSubmit()
        });
        prev.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                show()
            }
        });
        form.addEventListener('submit', e => {
            if (currentStep < 3 || !valid()) {
                e.preventDefault()
            }
        });
        show();
    </script>
</body>

</html>
