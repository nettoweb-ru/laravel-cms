<?php

namespace Netto\Console\Commands;

use App\Services\SearchService;
use Illuminate\Console\Command;

class RefreshSearchIndex extends Command
{
    protected $signature = 'cms:refresh-search {--force=0 : Truncate table before reindexing}';
    protected $description = 'Reindex search database';

    /**
     * @return void
     */
    public function handle(): void
    {
        (new SearchService(!empty($this->option('force'))))->reindex();
    }
}
