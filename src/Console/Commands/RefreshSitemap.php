<?php

namespace Netto\Console\Commands;

use App\Services\SitemapService;
use Illuminate\Console\Command;

class RefreshSitemap extends Command
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
