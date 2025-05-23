<?php

namespace Netto\Services;

use Illuminate\Support\Facades\File;

abstract class SitemapService
{
    /**
     * Regenerate sitemap.xml
     *
     * @return void
     */
    public function regenerate(): void
    {
        $file = public_path('sitemap.xml');
        if (File::exists($file)) {
            File::delete($file);
        }

        $xml = <<<X
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

X;
        foreach ($this->paths() as $item) {
            $xml .= <<<X
    <url>
        <loc>{$item['path']}</loc>

X;
            if (!empty($item['modified'])) {
                $xml .= <<<X
        <lastmod>{$item['modified']}</lastmod>

X;
            }
            $xml .= <<<X
    </url>

X;
        }
        $xml .= <<<X
</urlset>

X;
        File::put($file, $xml);
    }

    /**
     * @return array
     */
    abstract protected function paths(): array;
}
