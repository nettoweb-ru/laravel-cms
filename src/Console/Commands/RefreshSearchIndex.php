<?php

namespace Netto\Console\Commands;

use App\Services\SearchService;
use Netto\Console\Commands\Abstract\Command as BaseCommand;

class RefreshSearchIndex extends BaseCommand
{
    protected $signature = 'cms:refresh-search {--force=0 : Truncate table before reindexing}';
    protected $description = 'Reindex search database';

    /**
     * @return void
     */
    protected function action(): void
    {
        (new SearchService(!empty($this->option('force'))))->reindex();
    }
}
