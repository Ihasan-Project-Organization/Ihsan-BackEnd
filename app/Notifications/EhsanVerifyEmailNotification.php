<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class EhsanVerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('تأكيد وتوثيق البريد الإلكتروني | إحسان')
            ->view('emails.auth.verify-email', [
                'verificationUrl' => $verificationUrl,
                'user' => $notifiable,
            ]);
    }
}
