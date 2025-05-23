<?php

namespace App\Services;

use Netto\Services\SearchService as BaseService;

class SearchService extends BaseService
{
    public const MIN_NEEDLE_LENGTH = 4;

    protected int $delay = 500000;
    protected string $highlightClass = 'search-highlight';
    protected int $maxPreviewLength = 256;
    protected int $perPage = 10;

    /**
     * @return array
     */
    protected function paths(): array
    {
        return [];
    }
}
