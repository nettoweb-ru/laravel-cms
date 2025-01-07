<?php

namespace Netto\Services;

use Illuminate\Support\Facades\Mail;
use Netto\Mail\ReportLogs;

class ReportLogService
{
    private string $email;
    private array $files = [];
    private bool $delete;

    /**
     * @param string $email
     * @param array $files
     * @param bool $delete
     */
    public function __construct(string $email, array $files, bool $delete = true)
    {
        $this->email = $email;
        $this->files = $files;
        $this->delete = $delete;
    }

    /**
     * @return void
     */
    public function send(): void
    {
        $package = new \ZipArchive();
        $packagePath = storage_path('app/logs.zip');

        if ($package->open($packagePath, \ZipArchive::CREATE) === true) {
            foreach ($this->files as $file) {
                $package->addFile($file, basename($file));
            }

            $package->close();

            Mail::to($this->email)->send(new ReportLogs($packagePath));

            if (file_exists($packagePath)) {
                unlink($packagePath);
            }
        } else {
            foreach ($this->files as $file) {
                Mail::to($this->email)->send(new ReportLogs($file));
            }
        }

        if ($this->delete) {
            foreach ($this->files as $file) {
                unlink($file);
            }
        }
    }
}
