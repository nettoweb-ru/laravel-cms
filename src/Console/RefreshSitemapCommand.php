<?php

namespace Netto\Console;

use App\Services\SitemapService;
use Illuminate\Console\Command;

class RefreshSitemapCommand extends Command
{
    protected $signature = 'cms:refresh-sitemap';
    protected $description = 'Regenerate sitemap.xml';

    /**
     * @return void
     */
    public function handle(): void
    {
        (new SitemapService())->regenerate();
    }
}
