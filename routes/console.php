<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('requests:check-expirations', function () {
    \App\Models\ServiceRequest::processScheduleExpirations();
    $this->info('تمت معالجة الطلبات المتأخرة والمنتهية بنجاح.');
})->purpose('فحص ومعالجة الطلبات المتأخرة وغير المكتملة تلقائياً');

\Illuminate\Support\Facades\Schedule::command('requests:check-expirations')
    ->everyMinute()
    ->name('requests:check-expirations');

Artisan::command('mail:test {email}', function (string $email) {
    $this->info("جاري إرسال بريد تجريبي إلى: {$email}...");
    try {
        \Illuminate\Support\Facades\Mail::raw('مرحباً بك! هذه رسالة تأكيدية تفيد بنجاح إعداد خادم البريد لمنصة أنيس لرعاية كبار السن.', function ($message) use ($email) {
            $message->to($email)->subject('اختبار خدمة البريد | منصة أنيس');
        });
        $this->info("تم إرسال البريد بنجاح! تفقد صندوق الوارد في: {$email}");
    } catch (\Throwable $e) {
        $this->error("فشل إرسال البريد: " . $e->getMessage());
    }
})->purpose('إرسال بريد إلكتروني اختباري للتأكد من اتصال SMTP');

Artisan::command('mail:test-verification {email}', function (string $email) {
    $this->info("جاري إرسال رسالة تفعيل حساب تجريبية بقالب أنيس الرسمي إلى: {$email}...");
    try {
        $user = \App\Models\User::first() ?? new \App\Models\User();
        $user->setAttribute('id', $user->id ?: 1);
        $user->name = 'أحمد العبدالله';
        $user->email = $email;
        $user->notify(new \App\Notifications\EhsanVerifyEmailNotification());
        $this->info("تم إرسال قالب التفعيل الرسمي بنجاح! تفقد بريدك: {$email}");
    } catch (\Throwable $e) {
        $this->error("فشل إرسال رسالة التفعيل: " . $e->getMessage());
    }
})->purpose('إرسال قالب تفعيل الحساب الرسمي للتأكد من المظهر والروابط');


