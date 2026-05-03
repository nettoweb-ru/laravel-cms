<?php

namespace App\Services;

use Netto\Services\SearchService as BaseService;

class SearchService extends BaseService
{
    protected int $perPage = 10;

    /**
     * @return array
     */
    protected function paths(): array
    {
        return [];
    }
}
