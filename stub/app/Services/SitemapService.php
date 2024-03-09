<?php

namespace App\Services;

use Netto\Services\SitemapService as BaseService;

class SitemapService extends BaseService
{
    /**
     * @return array
     */
    protected function paths(): array
    {
        return [];
    }
}
