<?php

declare(strict_types=1);

namespace Netto\Services;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Netto\Services\SearchService as BaseService;

abstract class SiteSearchService extends BaseService
{
    private const TABLE = 'cms__search';
    private const MAX_NAME_LENGTH = 2048;

    protected function getBuilder(): Builder
    {
        return DB::table(self::TABLE);
    }

    protected function getItems(Builder $builder, string $query): array
    {
        $return = [];
        $length = mb_strlen($query);
        $cutLength = (int) floor((config('cms.search-max-preview-length') - $length) / 2);

        foreach ($builder->get() as $item) {
            $pos = mb_stripos($item->name, $query);

            if ($pos === false) {
                $relevance = self::MAX_NAME_LENGTH;
            } else {
                $relevance = $pos;
            }

            $return[$relevance][$item->id] = [
                'title' => $item->name,
                'link' => $item->url,
                'date' => $item->updated_at ? format_date($item->updated_at, \IntlDateFormatter::NONE) : '',
                'preview' => $this->getPreview($query, $length, $cutLength, $item->content),
            ];
        }

        ksort($return);
        return array_merge(...$return);
    }

    /**
     * @param string $needle
     * @param int $length
     * @param int $cutLength
     * @param string $content
     * @return string
     */
    protected function getPreview(string $needle, int $length, int $cutLength, string $content): string
    {
        $start = (int) mb_stripos($content, $needle);
        $afterOffset = $start + $length;
        $max = mb_strlen($content);

        $return = mb_substr($content, $start, $length);

        $beforeOffset = $start - $cutLength;
        $beforeLength = $cutLength;
        $afterLength = $cutLength;

        if ($beforeOffset < 0) {
            $afterLength += ($beforeOffset * -1);

            $beforeOffset = 0;
            $beforeLength = $start;
        }

        $check = $afterOffset + $afterLength;
        if ($check > $max) {
            $afterLength -= ($check - $max);
        }

        if ($beforeLength) {
            $return = mb_substr($content, $beforeOffset, $beforeLength).$return;
        }

        if ($afterLength) {
            $return .= mb_substr($content, $afterOffset, $afterLength);
        }

        $array = explode(' ', $return);
        array_pop($array);
        array_shift($array);
        $return = implode(' ', $array);

        $pos = mb_stripos($return, $needle);
        if ($pos !== false) {
            $return = mb_substr($return, 0, $pos).'<span class="'.config('cms.search-highlight-class').'">'.mb_substr($return, $pos, $length).'</span>'.mb_substr($return, ($pos + $length));
        }

        return $return;
    }

    protected function prepareBuilder(Builder &$builder, string $query): void
    {
        $builder->where('content', 'like', '%'.$query.'%')
            ->where('lang_id', get_current_language_id())
            ->orderBy('updated_at', 'desc');
    }
}
