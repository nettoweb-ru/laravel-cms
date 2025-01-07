<?php

namespace Netto\Console;

use Illuminate\Console\Command;
use Netto\Services\ReportLogService;

class ReportLogsCommand extends Command
{
    protected $signature = 'cms:report-logs';
    protected $description = 'Report logs to service email';

    /**
     * @return void
     */
    public function handle(): void
    {
        $email = config('cms.service_email');
        if (empty($email)) {
            return;
        }

        $logPath = storage_path('logs/laravel.log');
        if (!file_exists($logPath)) {
            return;
        }

        (new ReportLogService($email, [$logPath]))->send();
    }
}
