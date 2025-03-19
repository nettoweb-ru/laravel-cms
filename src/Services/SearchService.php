<?php

namespace Netto\Services;

use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

abstract class SearchService
{
    public const MIN_NEEDLE_LENGTH = 3;
    public const CHUNK_SIZE = 500;

    private const TABLE = 'cms__search';
    private const MAX_NAME_LENGTH = 2048;

    protected int $delay;
    protected int $perPage;
    protected int $maxPreviewLength;
    protected string $highlightClass;

    private array $paths = [];
    private array $deleteId = [];

    private bool $force;

    /**
     * @param bool $force
     */
    public function __construct(bool $force = false)
    {
        $this->force = $force;
    }

    /**
     * @param string $query
     * @param int $page
     * @return array
     */
    public function find(string $query, int $page = 1): array
    {
        $query = urldecode(strip_tags($query));
        $return = [
            'query' => $query,
            'list' => [],
            'navigation' => [
                'max' => 0,
                'current' => $page,
            ],
        ];

        if ($page < 1) {
            return $return;
        }

        if (mb_strlen($query) < static::MIN_NEEDLE_LENGTH) {
            return $return;
        }

        $collection = DB::table(self::TABLE)
            ->where('content', 'like', '%'.$query.'%')
            ->where('lang_id', LanguageService::getCurrentId())
            ->orderBy('updated_at', 'desc')
            ->get();

        $items = [];
        $length = mb_strlen($query);
        $cutLength = (int) floor(($this->maxPreviewLength - $length) / 2);
        foreach ($collection as $item) {
            $pos = mb_stripos($item->name, $query);

            if ($pos === false) {
                $relevance = self::MAX_NAME_LENGTH;
            } else {
                $relevance = $pos;
            }

            $items[$relevance][$item->id] = [
                'title' => $item->name,
                'link' => $item->url,
                'date' => $item->updated_at ? format_date($item->updated_at, \IntlDateFormatter::NONE) : '',
                'preview' => $this->preview($query, $length, $cutLength, $item->content),
            ];
        }

        ksort($items);

        $list = [];
        foreach ($items as $value) {
            $list = array_merge($list, $value);
        }

        $list = array_slice($list, ($page - 1) * $this->perPage, $this->perPage);

        if (empty($list)) {
            return $return;
        }

        $pagination = new LengthAwarePaginator($list, count($collection), $this->perPage, $page);

        $return['navigation']['max'] = $pagination->lastPage();
        $return['list'] = $list;

        return $return;
    }

    /**
     * @return void
     */
    public function reindex(): void
    {
        if ($this->force) {
            DB::statement("TRUNCATE TABLE `".self::TABLE."`");
        }

        foreach ($this->paths() as $data) {
            $this->paths[] = $data['path'];
        }

        if (!$this->force) {
            DB::table(self::TABLE)->select(['id', 'url'])->orderBy('updated_at', 'desc')->chunk(self::CHUNK_SIZE, function(Collection $collection) {
                foreach ($collection as $item) {
                    if (in_array($item->url, $this->paths)) {
                        unset($this->paths[array_search($item->url, $this->paths)]);
                    } else {
                        $this->deleteId[] = $item->id;
                        continue;
                    }

                    try {
                        [$langId, $name, $content, $updatedAt] = $this->parse($item->url);
                    } catch (\Throwable $throwable) {
                        Log::error($throwable->getMessage());
                        continue;
                    }

                    DB::table(self::TABLE)->where('id', $item->id)->update([
                        'lang_id' => $langId,
                        'name' => $name,
                        'url' => $item->url,
                        'content' => $content,
                        'updated_at' => $updatedAt,
                    ]);
                }
            });

            if ($this->deleteId) {
                DB::table(self::TABLE)->whereIn('id', $this->deleteId)->delete();
            }
        }

        foreach ($this->paths as $path) {
            try {
                [$langId, $name, $content, $updatedAt] = $this->parse($path);
            } catch (\Throwable $throwable) {
                Log::error($throwable->getMessage());
                continue;
            }

            DB::table(self::TABLE)->insert([
                'lang_id' => $langId,
                'name' => $name,
                'url' => $path,
                'content' => $content,
                'updated_at' => $updatedAt,
            ]);
        }
    }

    /**
     * @return array
     */
    abstract protected function paths(): array;

    /**
     * @param string $content
     * @return string
     */
    private function content(string $content): string
    {
        $content = str_replace([chr(9), chr(10), chr(13)], ' ', strip_tags($content));
        return implode(' ', array_filter(array_map('trim', explode(' ', $content))));
    }

    /**
     * @param string $url
     * @return array
     * @throws Exception
     */
    private function parse(string $url): array
    {
        $response = Http::get($url);
        if ($response->failed()) {
            throw new Exception("Request returned status {$response->status()}");
        }

        $headers = $response->headers();
        if (empty($headers['Content-Language'][0])) {
            throw new Exception("Unable to detect language for url {$url}");
        }

        $languages = LanguageService::getList();
        if (!array_key_exists($headers['Content-Language'][0], $languages)) {
            throw new Exception("Language {$headers['Content-Language'][0]} is not configured for url {$url}");
        }

        usleep($this->delay);

        $body = html_entity_decode($response->body());
        preg_match_all("/(.*)<title>(.*)<\/title>(.*)/", $body, $matches);

        $name = $url;
        if (!empty($matches[2][0])){
            $name = trim($matches[2][0]);
        }

        if (mb_strlen($name) > self::MAX_NAME_LENGTH) {
            $name = mb_substr($name, 0, self::MAX_NAME_LENGTH);
        }

        $content = preg_replace([
            "/<netto-skip-reindex>[\s\S]*?<\/netto-skip-reindex>/",
            "/<head>[\s\S]*?<\/head>/"
        ], '', $body);

        $updatedAt = null;
        if (!empty($headers['Last-Modified'][0])) {
            $date = Carbon::createFromFormat('D, d M Y H:i:s e', $headers['Last-Modified'][0], 'GMT');
            $date->setTimezone(date_default_timezone_get());
            $updatedAt = $date->format('Y-m-d H:i:s');
        }

        return [
            $languages[$headers['Content-Language'][0]]['id'],
            $name,
            $this->content($content),
            $updatedAt,
        ];
    }

    /**
     * @param string $needle
     * @param int $length
     * @param int $cutLength
     * @param string $content
     * @return string
     */
    private function preview(string $needle, int $length, int $cutLength, string $content): string
    {
        $start = mb_stripos($content, $needle);
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

        if ($this->highlightClass) {
            $pos = mb_stripos($return, $needle);
            if ($pos !== false) {
                $return = mb_substr($return, 0, $pos).'<span class="'.$this->highlightClass.'">'.mb_substr($return, $pos, $length).'</span>'.mb_substr($return, ($pos + $length));
            }
        }

        return $return;
    }
}
