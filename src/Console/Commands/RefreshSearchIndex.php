<?php

declare(strict_types=1);

namespace Netto\Console\Commands;

use App\Services\ReindexService;
use Illuminate\Console\Command;

class RefreshSearchIndex extends Command
{
    protected $signature = 'cms:refresh-search {--truncate=0 : Truncate table before reindexing}';
    protected $description = 'Reindex search database';

    /**
     * @return void
     */
    public function handle(): void
    {
        $service = new ReindexService();

        if (!empty($this->option('truncate'))) {
            $service->truncate = true;
        }

        $service->reindex();
    }
}
