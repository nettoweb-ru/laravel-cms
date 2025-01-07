<?php

namespace Netto\Mail;

use Composer\InstalledVersions;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ReportLogs extends Mailable
{
    use Queueable, SerializesModels;

    public string $fileName;

    public string $appName;
    public string $appUrl;

    public string $vCore;
    public string $vCurrency = '';
    public string $vStore = '';

    public string $date;

    /**
     * @param string $fileName
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
        $this->date = format_date(date('Y-m-d H:i:s'));

        $this->appName = env('APP_NAME', '');
        $this->appUrl = env('APP_URL', '');

        $this->vCore = InstalledVersions::getVersion('nettoweb/laravel-cms');
        $packages = InstalledVersions::getInstalledPackages();

        if (in_array('nettoweb/laravel-cms-currency', $packages)) {
            $this->vCurrency = InstalledVersions::getVersion('nettoweb/laravel-cms-currency');
        }

        if (in_array('nettoweb/laravel-cms-store', $packages)) {
            $this->vStore = InstalledVersions::getVersion('nettoweb/laravel-cms-store');
        }
    }

    /**
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Service logs',
        );
    }

    /**
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            html: 'cms::mail.logs',
            text: 'cms::mail.logs-text'
        );
    }

    /**
     * @return array
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->fileName),
        ];
    }
}
