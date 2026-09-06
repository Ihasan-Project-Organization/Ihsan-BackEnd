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

