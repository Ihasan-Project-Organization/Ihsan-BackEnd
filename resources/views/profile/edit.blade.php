<x-app-layout>
    <style>
        .profile-page{width:min(100%,880px);margin-inline:auto}

        /* ── Profile Banner ── */
        .profile-banner{
            position:relative;overflow:hidden;
            border-radius:20px;
            background:linear-gradient(145deg,#1e3212 0%,#2c4318 40%,#3a5824 100%);
            padding:28px;color:#fff;
            box-shadow:0 6px 24px rgba(44,67,24,.18);
            margin-bottom:20px;
        }
        .profile-banner::after{
            content:'';position:absolute;bottom:-30px;left:-30px;width:130px;height:130px;
            border-radius:50%;background:rgba(122,157,80,.2);pointer-events:none;
        }
        .profile-banner-inner{
            position:relative;z-index:1;
            display:flex;align-items:center;gap:20px;
        }
        .profile-avatar{
            width:80px;height:80px;flex:none;
            border-radius:18px;border:3px solid rgba(255,255,255,.15);
            object-fit:cover;
            box-shadow:0 4px 16px rgba(0,0,0,.2);
        }
        .profile-avatar-placeholder{
            display:grid;place-items:center;
            width:80px;height:80px;flex:none;
            border-radius:18px;
            background:rgba(255,255,255,.1);border:2px solid rgba(255,255,255,.12);
            font-size:28px;font-weight:900;color:#fff;
            box-shadow:0 4px 16px rgba(0,0,0,.15);
        }
        .profile-info{flex:1;min-width:0}
        .profile-info h2{font-size:22px;font-weight:900;margin:0 0 2px;display:flex;align-items:center;gap:8px;flex-wrap:wrap}
        .profile-role{
            display:inline-flex;align-items:center;
            background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.12);
            border-radius:20px;padding:3px 11px;
            font-size:10px;font-weight:700;color:rgba(223,230,213,.85);
        }
        .profile-info .profile-email{
            margin-top:6px;color:rgba(223,230,213,.8);
            font-size:12px;font-weight:600;font-family:monospace;direction:ltr;
        }
        .profile-info .profile-phone{
            margin-top:3px;color:rgba(223,230,213,.6);
            font-size:11px;font-weight:600;font-family:monospace;direction:ltr;
        }

        /* ── Settings Cards ── */
        .settings-stack{display:flex;flex-direction:column;gap:14px}

        .settings-card{
            background:#fff;border:1px solid #e4dfd6;border-radius:18px;
            padding:20px 22px;
            box-shadow:0 2px 10px rgba(44,67,24,.04);
            transition:box-shadow .25s ease;
        }
        .settings-card:hover{box-shadow:0 4px 18px rgba(44,67,24,.07)}

        .settings-card-header{
            display:flex;align-items:center;gap:12px;
            padding-bottom:14px;margin-bottom:16px;
            border-bottom:1px solid #f0ede7;
        }
        .settings-card-icon{
            width:40px;height:40px;flex:none;
            display:grid;place-items:center;
            border-radius:12px;font-size:16px;
        }
        .settings-card-icon.green{background:#edf6e8;color:#3d6125}
        .settings-card-icon.amber{background:#fef8ee;color:#b66c05}
        .settings-card-icon.blue{background:#eff6ff;color:#2563eb}
        .settings-card-icon.red{background:#fef2f2;color:#dc2626}

        .settings-card-header h2{font-size:15px;font-weight:900;color:#1e2e14;margin:0}
        .settings-card-header p{font-size:11px;color:#7e8878;font-weight:600;margin:2px 0 0}
        .settings-card-header .active-badge{
            margin-right:auto;font-size:9px;font-weight:800;
            background:#edf6e8;color:#2e6b16;padding:3px 9px;border-radius:8px;
            border:1px solid #c6ddb6;
        }

        /* Form styling */
        .settings-card label{font-size:11px;font-weight:800;color:#4a5545;margin-bottom:5px;display:block}
        .settings-card input[type="text"],
        .settings-card input[type="email"],
        .settings-card input[type="tel"],
        .settings-card input[type="password"]{
            width:100%;padding:10px 14px;border:1px solid #d8dfd0;
            border-radius:12px;font-size:13px;font-weight:600;color:#2d3748;
            background:#fafbf9;
            font-family:'Alexandria',Arial,sans-serif;
            transition:border-color .2s,box-shadow .2s;
        }
        .settings-card input:focus{
            border-color:#7a9d50;outline:none;
            box-shadow:0 0 0 3px rgba(122,157,80,.12);
            background:#fff;
        }

        /* File input */
        .profile-photo-upload{
            display:flex;align-items:center;gap:14px;
            padding:14px;border-radius:14px;
            background:#fafbf9;border:1px dashed #d8dfd0;
        }
        .profile-photo-preview{
            width:56px;height:56px;flex:none;border-radius:14px;
            object-fit:cover;border:2px solid #dfe6d5;
        }
        .profile-photo-placeholder{
            width:56px;height:56px;flex:none;
            display:grid;place-items:center;
            border-radius:14px;background:#eef2e8;
            font-size:18px;font-weight:900;color:#2c4318;
            border:1px solid #d8dfd0;
        }
        .profile-photo-upload input[type="file"]{
            font-size:11px;color:#64748b;
        }
        .profile-photo-upload input[type="file"]::file-selector-button{
            background:#edf6e8;color:#2c4318;border:none;
            padding:7px 14px;border-radius:10px;
            font-size:11px;font-weight:700;cursor:pointer;
            margin-left:10px;transition:background .2s;
        }
        .profile-photo-upload input[type="file"]::file-selector-button:hover{background:#dfe9d5}
        .profile-photo-hint{font-size:10px;color:#94a3b8;margin-top:4px;font-weight:600}

        .form-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}
        .form-row{margin-bottom:12px}
        .form-hint{font-size:10px;color:#94a3b8;margin-top:4px;font-weight:600}

        .save-btn{
            display:inline-flex;align-items:center;gap:6px;
            background:#2c4318;color:#fff;border:none;
            padding:10px 22px;border-radius:12px;
            font-size:12px;font-weight:800;cursor:pointer;
            transition:all .25s ease;
            box-shadow:0 2px 8px rgba(44,67,24,.15);
        }
        .save-btn:hover{background:#3d5a26;transform:translateY(-1px);box-shadow:0 4px 14px rgba(44,67,24,.2)}
        .save-success{font-size:11px;font-weight:700;color:#16a34a;display:inline-flex;align-items:center;gap:4px}

        /* Toggle option rows */
        .toggle-row{
            display:flex;align-items:center;justify-content:space-between;gap:12px;
            padding:12px 14px;border-radius:13px;
            background:#fafbf9;border:1px solid #ebeee7;
            transition:background .2s;
        }
        .toggle-row:hover{background:#f4f7f0}
        .toggle-row-info{display:flex;align-items:center;gap:10px}
        .toggle-row-icon{
            width:34px;height:34px;flex:none;display:grid;place-items:center;
            border-radius:10px;font-size:13px;
        }
        .toggle-row h3{font-size:12px;font-weight:800;color:#2d3748;margin:0}
        .toggle-row p{font-size:10px;color:#7e8878;margin:2px 0 0;font-weight:600}

        /* Toggle switch */
        .toggle-switch{position:relative;display:inline-block;width:42px;height:24px;cursor:pointer}
        .toggle-switch input{opacity:0;width:0;height:0}
        .toggle-track{
            position:absolute;inset:0;background:#cbd5e1;border-radius:12px;
            transition:background .25s;
        }
        .toggle-track::after{
            content:'';position:absolute;top:2px;left:2px;width:20px;height:20px;
            background:#fff;border-radius:50%;transition:transform .25s;
            box-shadow:0 1px 3px rgba(0,0,0,.15);
        }
        .toggle-switch input:checked+.toggle-track{background:#2c4318}
        .toggle-switch input:checked+.toggle-track::after{transform:translateX(18px)}

        /* Font size buttons */
        .font-size-panel{
            padding:14px;border-radius:14px;
            background:#fafbf9;border:1px solid #ebeee7;
        }
        .font-size-row{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:12px;flex-wrap:wrap}
        .font-size-row h3{font-size:12px;font-weight:800;color:#2d3748;display:flex;align-items:center;gap:8px}
        .font-size-row .current-size{
            font-size:10px;font-weight:800;color:#2c4318;
            background:#fff;padding:3px 10px;border-radius:8px;
            border:1px solid #d8dfd0;
        }
        .font-size-btns{display:grid;grid-template-columns:repeat(5,1fr);gap:6px}
        .font-size-btn{
            display:flex;flex-direction:column;align-items:center;justify-content:center;
            padding:10px 8px;border-radius:10px;border:1px solid #ddd8cd;
            background:#fff;color:#4a5545;
            font-size:11px;font-weight:700;cursor:pointer;
            transition:all .2s ease;
        }
        .font-size-btn:hover{border-color:#7a9d50;background:#f4f7f0}
        .font-size-btn.active{
            background:#2c4318;color:#fff;border-color:#2c4318;
            box-shadow:0 2px 8px rgba(44,67,24,.2);
        }
        .font-size-btn small{font-size:9px;opacity:.7;margin-top:2px;font-weight:600}

        /* Danger zone */
        .danger-card{border-color:#f5c6c6!important}
        .danger-card:hover{box-shadow:0 4px 18px rgba(220,38,38,.06)!important}

        .delete-btn{
            display:inline-flex;align-items:center;gap:6px;
            background:none;color:#b91c1c;border:1px solid #fca5a5;
            padding:8px 18px;border-radius:10px;
            font-size:11px;font-weight:800;cursor:pointer;
            transition:all .2s;margin-top:10px;
        }
        .delete-btn:hover{background:#fef2f2;border-color:#f87171}
        .delete-btn i{font-size:12px}

        /* Logout */
        .logout-full-btn{
            display:flex;align-items:center;justify-content:center;gap:8px;
            width:100%;padding:12px;border-radius:14px;
            background:#fef2f2;border:1px solid #fecaca;
            color:#b91c1c;font-size:12px;font-weight:800;
            cursor:pointer;transition:all .2s;
        }
        .logout-full-btn:hover{background:#fee2e2;border-color:#fca5a5}
        .logout-full-btn i{font-size:14px}

        @media(max-width:640px){
            .profile-banner{padding:20px;border-radius:16px}
            .profile-banner-inner{flex-direction:column;text-align:center;gap:14px}
            .profile-info h2{justify-content:center}
            .profile-info .profile-email,.profile-info .profile-phone{text-align:center}
            .settings-card{padding:16px;border-radius:14px}
            .form-grid{grid-template-columns:1fr}
            .font-size-btns{grid-template-columns:repeat(3,1fr)}
        }
    </style>

    <div class="profile-page">

        {{-- ═══════ Profile Banner ═══════ --}}
        <div class="profile-banner">
            <div class="profile-banner-inner">
                @if ($user->profile_photo_url)
                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="profile-avatar">
                @else
                    <div class="profile-avatar-placeholder">{{ mb_substr($user->name, 0, 1) }}</div>
                @endif
                <div class="profile-info">
                    <h2>
                        {{ $user->name }}
                        <span class="profile-role">
                            @if ($user->isAdmin()) مدير النظام
                            @elseif ($user->isProvider()) مقدم خدمة (Tier {{ $user->serviceProviderProfile?->tier ?? 1 }})
                            @else كبير السن / مستفيد
                            @endif
                        </span>
                    </h2>
                    <div class="profile-email">{{ $user->email }}</div>
                    @if ($user->phone_number)
                        <div class="profile-phone"><i class="fa-solid fa-phone" style="font-size:10px;margin-left:4px"></i> {{ $user->phone_number }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="settings-stack">

            {{-- ═══════ 1. Profile Information ═══════ --}}
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="settings-card-icon green"><i class="fa-solid fa-user"></i></span>
                    <div>
                        <h2>الملف الشخصي</h2>
                        <p>تعديل معلوماتك الشخصية وصورتك ورقم هاتفك المعتمد.</p>
                    </div>
                </div>

                <form id="send-verification" method="POST" action="{{ route('verification.send') }}">@csrf</form>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    {{-- Photo --}}
                    <div class="form-row">
                        <label>الصورة الشخصية (اختيارية)</label>
                        <div class="profile-photo-upload">
                            @if ($user->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="profile-photo-preview">
                            @else
                                <div class="profile-photo-placeholder">{{ mb_substr($user->name, 0, 1) }}</div>
                            @endif
                            <div style="flex:1;min-width:0">
                                <input id="profile_picture" name="profile_picture" type="file" accept="image/*">
                                <div class="profile-photo-hint">صيغ الصور المدعومة: JPG, PNG (بحد أقصى 2MB)</div>
                                <x-input-error class="mt-1" :messages="$errors->get('profile_picture')" />
                            </div>
                        </div>
                    </div>

                    {{-- Name + Email --}}
                    <div class="form-grid">
                        <div>
                            <label for="name">الاسم الكامل</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autocomplete="name">
                            <x-input-error class="mt-1" :messages="$errors->get('name')" />
                        </div>
                        <div>
                            <label for="email">البريد الإلكتروني</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
                            <x-input-error class="mt-1" :messages="$errors->get('email')" />
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="form-row" style="margin-top:12px">
                        <label for="phone_number">رقم الهاتف للتواصل</label>
                        <input id="phone_number" name="phone_number" type="tel"
                            value="{{ old('phone_number', $user->phone_number) }}"
                            placeholder="05XXXXXXXX" dir="ltr">
                        <div class="form-hint">يستخدم للتنسيق بين المستفيد ومقدم الخدمة بعد التوكيل الرسمي للطلب.</div>
                        <x-input-error class="mt-1" :messages="$errors->get('phone_number')" />
                    </div>

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div style="margin:12px 0;padding:10px 14px;border-radius:12px;background:#fffbeb;border:1px solid #fde68a;font-size:11px;color:#92400e;font-weight:700;display:flex;align-items:center;justify-content:space-between">
                            <span>البريد الإلكتروني غير موثق حتى الآن.</span>
                            <button form="send-verification" style="font-weight:800;text-decoration:underline;color:#78350f;cursor:pointer;background:none;border:none;font-size:11px">إعادة إرسال رابط التوثيق</button>
                        </div>
                    @endif

                    <div style="display:flex;align-items:center;gap:12px;margin-top:16px">
                        <button type="submit" class="save-btn">
                            <i class="fa-solid fa-check"></i> حفظ التغييرات
                        </button>
                        @if (session('status') === 'profile-updated')
                            <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="save-success">
                                <i class="fa-solid fa-circle-check"></i> تم حفظ التغييرات بنجاح
                            </span>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ═══════ 2. Password ═══════ --}}
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="settings-card-icon blue"><i class="fa-solid fa-lock"></i></span>
                    <div>
                        <h2>كلمة المرور والأمان</h2>
                        <p>استخدم كلمة مرور قوية لا تقل عن ثمانية أحرف لضمان حماية حسابك.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="form-row">
                        <label for="current_password">كلمة المرور الحالية</label>
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password">
                        <x-input-error class="mt-1" :messages="$errors->updatePassword->get('current_password')" />
                    </div>

                    <div class="form-grid">
                        <div>
                            <label for="new_password">كلمة المرور الجديدة</label>
                            <input id="new_password" name="password" type="password" autocomplete="new-password">
                            <x-input-error class="mt-1" :messages="$errors->updatePassword->get('password')" />
                        </div>
                        <div>
                            <label for="new_password_confirmation">تأكيد كلمة المرور</label>
                            <input id="new_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
                        </div>
                    </div>

                    <div style="display:flex;align-items:center;gap:12px;margin-top:16px">
                        <button type="submit" class="save-btn">
                            <i class="fa-solid fa-shield-check"></i> تحديث كلمة المرور
                        </button>
                        @if (session('status') === 'password-updated')
                            <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="save-success">
                                <i class="fa-solid fa-circle-check"></i> تم تحديث كلمة المرور بنجاح
                            </span>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ═══════ 3. Display Preferences ═══════ --}}
            <div class="settings-card">
                <div class="settings-card-header">
                    <span class="settings-card-icon amber"><i class="fa-solid fa-palette"></i></span>
                    <div>
                        <h2>تفضيلات الواجهة والقراءة</h2>
                        <p>خيارات بصرية مساعدة لتسهيل القراءة وتجربة الاستخدام.</p>
                    </div>
                    <span class="active-badge">مفعل</span>
                </div>

                <div style="display:flex;flex-direction:column;gap:10px">
                    {{-- Font Size --}}
                    <div class="font-size-panel" x-data="{
                        scale: (function() {
                            try { return parseInt(localStorage.getItem('ihsan_font_scale')) || 100; } catch (e) { return 100; }
                        })(),
                        apply(val) {
                            this.scale = parseInt(val);
                            document.documentElement.style.fontSize = this.scale + '%';
                            try { localStorage.setItem('ihsan_font_scale', this.scale); } catch (e) {}
                        }
                    }">
                        <div class="font-size-row">
                            <h3>
                                <span style="display:inline-grid;width:28px;height:28px;place-items:center;border-radius:8px;background:#fef8ee;color:#b66c05;font-size:11px;font-weight:900">TT</span>
                                حجم الخط
                            </h3>
                            <span class="current-size"
                                x-text="scale === 90 ? 'صغير (90%)' : (scale === 100 ? 'عادي (100%)' : (scale === 110 ? 'متوسط (110%)' : (scale === 120 ? 'كبير (120%)' : 'كبير جداً (130%)')))">
                            </span>
                        </div>
                        <div class="font-size-btns">
                            <button type="button" @click="apply(90)" class="font-size-btn" :class="scale === 90 && 'active'">صغير<small>90%</small></button>
                            <button type="button" @click="apply(100)" class="font-size-btn" :class="scale === 100 && 'active'">عادي<small>افتراضي</small></button>
                            <button type="button" @click="apply(110)" class="font-size-btn" :class="scale === 110 && 'active'">متوسط<small>110%</small></button>
                            <button type="button" @click="apply(120)" class="font-size-btn" :class="scale === 120 && 'active'">كبير<small>120%</small></button>
                            <button type="button" @click="apply(130)" class="font-size-btn" :class="scale === 130 && 'active'">كبير جداً<small>130%</small></button>
                        </div>
                    </div>

                    {{-- Sound notifications --}}
                    <div class="toggle-row">
                        <div class="toggle-row-info">
                            <span class="toggle-row-icon" style="background:#edf6e8;color:#3d6125"><i class="fa-solid fa-volume-high"></i></span>
                            <div>
                                <h3>إشعارات الصوت</h3>
                                <p>تشغيل أو إيقاف صوت التنبيهات عند وصول إشعار جديد</p>
                            </div>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-track"></span>
                        </label>
                    </div>

                    {{-- Help --}}
                    <div class="toggle-row">
                        <div class="toggle-row-info">
                            <span class="toggle-row-icon" style="background:#f1f5f9;color:#475569"><i class="fa-solid fa-circle-question"></i></span>
                            <div>
                                <h3>المساعدة والدعم الفني</h3>
                                <p>تواصل مع فريق منصة أنيس لأي استفسار أو إرشاد</p>
                            </div>
                        </div>
                        <span style="font-size:10px;font-weight:800;color:#2c4318;background:#edf6e8;padding:4px 10px;border-radius:8px;border:1px solid #c6ddb6">الدعم متاح 24/7</span>
                    </div>
                </div>
            </div>

            {{-- ═══════ 4. Danger Zone ═══════ --}}
            <div class="settings-card danger-card">
                <div class="settings-card-header" style="border-bottom-color:#fee2e2">
                    <span class="settings-card-icon red"><i class="fa-solid fa-triangle-exclamation"></i></span>
                    <div>
                        <h2 style="color:#991b1b">حذف الحساب</h2>
                        <p>سيتم حذف الحساب وبياناته ووثائقه نهائيًا.</p>
                    </div>
                </div>
                <button type="button" x-data x-on:click.prevent="$dispatch('open-modal','confirm-user-deletion')" class="delete-btn">
                    <i class="fa-solid fa-trash-can"></i> حذف حسابي
                </button>
                <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
                    <form method="POST" action="{{ route('profile.destroy') }}" class="p-6 text-right sm:p-8">
                        @csrf
                        @method('delete')
                        <h2 style="font-size:18px;font-weight:900;color:#1e293b">هل أنت متأكد من حذف الحساب؟</h2>
                        <p style="margin-top:8px;font-size:13px;color:#64748b;line-height:1.7">أدخل كلمة المرور لتأكيد الحذف النهائي.</p>
                        <input name="password" type="password" placeholder="كلمة المرور"
                            style="margin-top:16px;width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:12px;font-size:13px">
                        <x-input-error class="mt-2" :messages="$errors->userDeletion->get('password')" />
                        <div style="margin-top:18px;display:flex;justify-content:flex-end;gap:10px">
                            <button type="button" x-on:click="$dispatch('close')"
                                style="padding:9px 18px;border-radius:10px;border:1px solid #d1d5db;font-weight:700;font-size:12px;cursor:pointer;background:#fff">إلغاء</button>
                            <button style="padding:9px 18px;border-radius:10px;background:#b91c1c;color:#fff;font-weight:700;font-size:12px;cursor:pointer;border:none">تأكيد الحذف</button>
                        </div>
                    </form>
                </x-modal>
            </div>

            {{-- ═══════ 5. Logout ═══════ --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-full-btn">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    تسجيل الخروج من الحساب
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
