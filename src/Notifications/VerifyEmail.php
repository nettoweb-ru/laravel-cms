<?php

namespace Netto\Notifications;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail as BaseNotification;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends BaseNotification
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
                ->subject(__('main.mail_subject_verify_email'))
                ->view('cms::mail.verify-email', ['url' => $this->verificationUrl($notifiable)])
                ->to($notifiable->getAttribute('email'));
        }

        return parent::toMail($notifiable);
    }
}
