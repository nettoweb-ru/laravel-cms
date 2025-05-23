<?php

namespace Netto\Notifications;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as BaseNotification;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends BaseNotification
{
    /**
     * @param $notifiable
     * @return MailMessage|Mailable
     */
    public function toMail($notifiable): MailMessage|Mailable
    {
        /** @var User $notifiable */
        if ($notifiable->isAdministrator()) {
            return (new Mailable())
                ->subject(__('main.mail_subject_reset_password'))
                ->view('cms::mail.reset-password', ['url' => $this->resetUrl($notifiable)])
                ->to($notifiable->getAttribute('email'));
        }

        return parent::toMail($notifiable);
    }
}
