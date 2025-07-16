<?php

namespace Netto\Services;

class ReadLogService
{
    private array $files = [];
    private int $max;

    /**
     * @param array $files
     * @param int $max
     */
    public function __construct(array $files, int $max)
    {
        $this->max = abs($max);

        foreach ($files as $file) {
            $path = storage_path('logs/'.$file);

            if (file_exists($path)) {
                $this->files[$file] = [
                    'path' => $path,
                    'items' => [],
                ];
            }
        }
    }

    /**
     * @return array
     */
    public function read(): array
    {
        foreach ($this->files as $file => $data) {
            $handler = fopen($data['path'], 'r');
            fseek($handler, filesize($data['path']));

            $pos = ftell($handler);
            $items = [];
            $chunk = '';

            while ($pos > 0) {
                $pos -= 1;
                if ($pos < 0) {
                    $pos = 0;
                }

                fseek($handler, $pos);
                $chunk = fread($handler, 1).$chunk;

                preg_match_all("/^\[([0-9]{4})\-([0-9]{2})\-([0-9]{2})\s([0-9]{2}):([0-9]{2}):([0-9]{2})\](.*)/", $chunk, $matches);

                if (empty($matches[0])) {
                    continue;
                }

                foreach ($matches[0] as $item) {
                    $items[] = trim($item);
                    if (count($items) == $this->max) {
                        break 2;
                    }
                }

                $chunk = '';
            }

            fclose($handler);

            if (empty($items)) {
                continue;
            }

            foreach ($items as $item) {
                preg_match("/^\[(.*?)\]\s(.*?)\.(.*?):\s(.*)$/", $item, $matches);
                $this->files[$file]['items'][] = [
                    'logged_at' => $matches[1],
                    'env' => $matches[2],
                    'level' => strtolower($matches[3]),
                    'message' => $matches[4],
                ];
            }
        }

        return $this->files;
    }
}
