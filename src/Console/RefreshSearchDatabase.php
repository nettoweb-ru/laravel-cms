<?php

namespace Netto\Console;

use App\Services\SearchService;
use Illuminate\Console\Command;

class RefreshSearchDatabase extends Command
{
    protected $signature = 'cms:refresh-search';
    protected $description = 'Reindex search database';

    /**
     * @return void
     */
    public function handle(): void
    {
        (new SearchService())->reindex();
    }
}
