<?php

declare(strict_types=1);

namespace Netto\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\{DB, Http, Log};
use Illuminate\Http\Client\ConnectionException;
use Netto\Exceptions\NettoException;

abstract class ReindexService
{
    public bool $truncate = false;

    protected array $paths = [];
    protected array $deleteId = [];

    private const TABLE = 'cms__search';
    private const CHUNK_SIZE = 500;
    private const MAX_NAME_LENGTH = 2048;

    public function reindex(): void
    {
        if ($this->truncate) {
            DB::statement("TRUNCATE TABLE `".self::TABLE."`");
        }

        foreach ($this->paths() as $data) {
            $this->paths[] = $data['path'];
        }

        $delay = config('cms.search-reindex-delay');

        if (!$this->truncate) {
            DB::table(self::TABLE)->select(['id', 'url'])->orderBy('updated_at', 'desc')->chunk(self::CHUNK_SIZE, function(Collection $collection) use ($delay) {
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

                    usleep($delay);
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

            usleep($delay);
        }
    }

    abstract protected function paths(): array;

    /**
     * @param string $string
     * @return string
     */
    private function content(string $string): string
    {
        $string = preg_replace([
            "/<netto-skip-reindex>[\s\S]*?<\/netto-skip-reindex>/",
            "/<head>[\s\S]*?<\/head>/"
        ], '', $string);

        $string = preg_replace('/<[^>]*>/', ' ', $string);
        return implode(' ', array_filter(array_map('trim', explode(' ', $string))));
    }

    /**
     * @param string $url
     * @return array
     * @throws NettoException|ConnectionException
     */
    private function parse(string $url): array
    {
        $response = Http::get($url);
        if ($response->failed()) {
            throw new NettoException("Request returned status {$response->status()}");
        }

        $headers = $response->headers();
        if (empty($headers['Content-Language'][0])) {
            throw new NettoException("Unable to detect language for url {$url}");
        }

        $languages = get_language_list();
        if (!array_key_exists($headers['Content-Language'][0], $languages)) {
            throw new NettoException("Language {$headers['Content-Language'][0]} is not configured for url {$url}");
        }

        $body = html_entity_decode($response->body());
        preg_match_all("/(.*)<title>(.*)<\/title>(.*)/", $body, $matches);

        $name = $url;
        if (!empty($matches[2][0])){
            $name = trim($matches[2][0]);
        }

        if (mb_strlen($name) > self::MAX_NAME_LENGTH) {
            $name = mb_substr($name, 0, self::MAX_NAME_LENGTH);
        }

        $content = $this->content($body);

        preg_match_all("/(.*)<meta name=\"keywords\" content=\"(.*)\">(.*)/", $body, $matches);
        if (!empty($matches[2][0])) {
            $content .= ' '.trim($matches[2][0]);
        }

        preg_match_all("/(.*)<meta name=\"description\" content=\"(.*)\">(.*)/", $body, $matches);
        if (!empty($matches[2][0])) {
            $content .= ' '.trim($matches[2][0]);
        }

        $updatedAt = null;
        if (!empty($headers['Last-Modified'][0])) {
            $date = Carbon::createFromFormat('D, d M Y H:i:s e', $headers['Last-Modified'][0], 'GMT');
            $date->setTimezone(date_default_timezone_get());
            $updatedAt = $date->format('Y-m-d H:i:s');
        }

        return [
            $languages[$headers['Content-Language'][0]]['id'],
            $name,
            trim($content),
            $updatedAt,
        ];
    }
}
