<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class EhsanResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('استعادة كلمة المرور | إحسان')
            ->view('emails.auth.reset-password', [
                'resetUrl' => $this->resetUrl($notifiable),
                'user' => $notifiable,
            ]);
    }
}
