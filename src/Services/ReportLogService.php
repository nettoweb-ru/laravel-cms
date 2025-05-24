<?php

namespace Netto\Services;

use Illuminate\Support\Facades\{File, Notification};
use Illuminate\Support\Str;
use Netto\Notifications\ReportLogs;
use App\Models\User;

class ReportLogService
{
    private User $user;
    private array $files = [];
    private string $zipPath = '';

    /**
     * @param string $email
     * @param array $files
     */
    public function __construct(string $email, array $files)
    {
        $user = new User();
        $user->setAttribute('email', $email);

        $this->user = $user;
        $this->files = $files;
    }

    /**
     * @return void
     */
    public function send(): void
    {
        Notification::send($this->user, new ReportLogs($this->createZip() ? $this->zipPath : $this->files));

        $unlink = $this->files;

        if ($this->zipPath) {
            $unlink[] = $this->zipPath;
        }

        File::delete($unlink);
    }

    /**
     * @return bool
     */
    private function createZip(): bool
    {
        $archive = new \ZipArchive();
        $zipPath = storage_path('app/'.Str::random(40).'.zip');

        if ($archive->open($zipPath, \ZipArchive::CREATE) === true) {
            foreach ($this->files as $file) {
                if (!$archive->addFile($file, basename($file))) {
                    return false;
                }
            }

            if (!$archive->close()) {
                return false;
            }

            $this->zipPath = $zipPath;
            return true;
        }

        return false;
    }
}
