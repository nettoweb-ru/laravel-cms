<?php

namespace Netto\Console\Commands;

use App\Services\SitemapService;
use Netto\Console\Commands\Abstract\Command as BaseCommand;

class RefreshSitemap extends BaseCommand
{
    protected $signature = 'cms:refresh-sitemap';
    protected $description = 'Regenerate sitemap.xml';

    /**
     * @return void
     */
    protected function action(): void
    {
        (new SitemapService())->regenerate();
    }
}
