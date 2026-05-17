<?php

declare(strict_types=1);

namespace Netto\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\{Cache, Log};
use Illuminate\Pagination\LengthAwarePaginator;

abstract class SearchService
{
    protected int $perPage = 10;

    abstract protected function getBuilder(): Builder;

    abstract protected function getItems(Builder $builder, string $query): array;

    abstract protected function prepareBuilder(Builder &$builder, string $query): void;

    public function find(string $query, int $page = 1): array
    {
        $original = $this->sanitize($query);
        $min = config('cms.search-min-query-length');

        if (($page < 1) || (mb_strlen($original) < $min)) {
            return [
                'query' => $original,
                'modified' => false,
                'results' => [
                    'list' => [],
                    'navigation' => [
                        'max' => 0,
                        'current' => $page,
                    ],
                ],
            ];
        }

        [$items, $modified] = Cache::remember(
            $this->getCacheKey($original),
            $this->getCacheTime(),
            function() use ($original, $min): array {
                $locale = app()->getLocale();
                Log::channel($this->getLogChannelId())->info("[{$locale}] ".$original);
                $modified = $original;

                $builder = $this->getBuilder();
                $this->prepareBuilder($builder, $original);

                if (!$builder->count()) {
                    $array = explode(' ', preg_replace("/^[a-zA-Zа-яА-ЯёЁ]+$/", '', $original));

                    if (count($array) > 1) {
                        usort($array, function($a, $b) {
                            return strlen($a) <=> strlen($b);
                        });

                        foreach (array_reverse($array) as $chunk) {
                            if (mb_strlen($chunk) < $min) {
                                continue;
                            }

                            $builder = $this->getBuilder();
                            $this->prepareBuilder($builder, $chunk);

                            if ($builder->count()) {
                                $modified = $chunk;
                                break;
                            }
                        }
                    }
                }

                return [$this->getItems($builder, $modified), $modified];
            }
        );

        $list = array_slice($items, ($page - 1) * $this->perPage, $this->perPage);
        $pagination = new LengthAwarePaginator($list, count($items), $this->perPage, $page);

        return [
            'query' => $modified,
            'modified' => !($original == $modified),
            'results' => [
                'list' => $list,
                'navigation' => [
                    'max' => $pagination->lastPage(),
                    'current' => $page,
                ],
            ],
        ];
    }

    protected function getLogChannelId(): string
    {
        return 'search';
    }

    protected function getCacheKey(string $query): string
    {
        $locale = app()->getLocale();
        return $locale.'|search|'.md5($query);
    }

    protected function getCacheTime(): int
    {
        return config('cms.search-cache-time');
    }

    protected function sanitize(string $string): string
    {
        return urldecode(strip_tags($string));
    }
}
