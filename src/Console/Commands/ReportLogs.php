<?php

namespace Netto\Console\Commands;

use Netto\Console\Commands\Abstract\Command as BaseCommand;
use Netto\Services\ReportLogService;

class ReportLogs extends BaseCommand
{
    protected $signature = 'cms:report-logs';
    protected $description = 'Report logs to service email';

    /**
     * @return void
     */
    protected function action(): void
    {
        $email = config('cms.report_logs.email');
        if (empty($email)) {
            return;
        }

        $logs = [];
        foreach (config('cms.report_logs.files', []) as $file => $delete) {
            $path = storage_path('logs/'.$file);
            if (file_exists($path)) {
                $logs[$path] = $delete;
            }
        }

        if (empty($logs)) {
            return;
        }

        (new ReportLogService($email, $logs))->send();
    }
}
