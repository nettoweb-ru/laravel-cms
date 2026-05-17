<?php

declare(strict_types=1);

namespace App\Services;

use Netto\Services\ReindexService as BaseService;

class ReindexService extends BaseService
{
    protected function paths(): array
    {
        return [];
    }
}
