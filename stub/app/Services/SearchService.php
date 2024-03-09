<?php

namespace App\Services;

use Netto\Services\SearchService as BaseService;

class SearchService extends BaseService
{
    public const MIN_NEEDLE_LENGTH = 4;

    protected int $delay = 500000;
    protected int $perPage = 10;
    protected int $maxPreviewLength = 256;
    protected string $highlightClass = 'search-highlight';

    /**
     * @return array
     */
    protected function paths(): array
    {
        return [];
    }
}
