<?php

namespace Netto\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;

class ReportLogs extends Notification
{
    use Queueable;

    private string $date;
    private string $name;
    private string|array $paths;
    private array $versions;
    private string $url;

    /**
     * @param string|array $attachments
     */
    public function __construct(string|array $attachments)
    {
        $this->paths = $attachments;
        $this->date = format_date(time());

        $this->name = config('app.name');
        $this->url = config('app.url');

        $this->versions = get_versions();
    }

    /**
     * @param object $notifiable
     * @return string[]
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * @param object $notifiable
     * @return Mailable
     */
    public function toMail(object $notifiable): Mailable
    {
        return (new Mailable())
            ->attachMany((array) $this->paths)
            ->subject(__('main.mail_subject_service_logs'))
            ->view('cms::mail.report-logs', $this->toArray($notifiable))
            ->to($notifiable->email);
    }

    /**
     * @param object $notifiable
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        return [
            'header' => __('main.mail_subject_service_logs'),
            'date' => $this->date,
            'name' => $this->name,
            'versions' => $this->versions,
            'url' => $this->url,
        ];
    }
}
