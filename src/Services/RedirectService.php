<?php

declare(strict_types=1);

namespace Netto\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Netto\Models\Redirect;

abstract class RedirectService
{
    /**
     * Process request to non-existing route.
     *
     * @param Request $request
     * @return RedirectResponse|null
     */
    public static function getRedirect(Request $request): ?RedirectResponse
    {
        $canonical = self::getHost($request);
        $uri = rtrim($request->getRequestUri(), '/');

        foreach (Redirect::query()->where('is_active', '1')->orderBy('source')->get() as $item) {
            $source = $item->getAttribute('source');
            $destination = $item->getAttribute('destination');
            $status = $item->getAttribute('status');

            if ($item->getAttribute('is_regexp')) {
                try {
                    preg_match('/^'.str_replace(['/', '?', '='], ['\\/', '\\?', '\\='], $source).'$/', $uri, $results);
                } catch (\Throwable $throwable) {
                    Log::error($throwable->getMessage());
                    continue;
                }

                unset($results[0]);

                if ($results) {
                    if (is_null($destination)) {
                        abort($status);
                    }

                    foreach ($results as $key => $value) {
                        $destination = str_replace("\${$key}", $value, $destination);
                    }

                    return redirect()->intended($canonical.$destination, $status);
                }
            } else if ($source == $uri) {
                if (is_null($destination)) {
                    abort($status);
                }

                return redirect()->intended($canonical.$destination, $status);
            }
        }

        return null;
    }

    /**
     * Process request to existing route.
     *
     * @param Request $request
     * @return RedirectResponse|null
     */
    public static function processRequest(Request $request): ?RedirectResponse
    {
        if ($redirect = self::getRedirect($request)) {
            return $redirect;
        }

        $path = $request->path();
        $query = $request->getQueryString();

        if ($path == '/') {
            $path = '';
        } else {
            $path = "/{$path}";
        }

        if ($query) {
            if (empty($path)) {
                $path .= '/';
            }

            $path .= "?{$query}";
        }

        $canonical = self::getHost($request).$path;

        $uri = $request->getRequestUri();
        if ($uri == '/') {
            $uri = '';
        }

        $requested = ($request->isSecure() ? 'https' : 'http').'://'.$request->getHttpHost().$uri;

        if ($requested == $canonical) {
            return null;
        }

        return redirect()->intended($canonical, 301);
    }

    /**
     * Get canonical hostname.
     *
     * @param Request $request
     * @return string
     */
    private static function getHost(Request $request): string
    {
        $return = (config('cms.redirects.https') ? 'https' : 'http').'://';

        if (config('cms.redirects.www')) {
            $return .= 'www.';
        }

        $host = $request->getHttpHost();

        if (str_starts_with($host, 'www.')) {
            $return .= ltrim($host, 'w.');
        } else {
            $return .= $host;
        }

        return $return;
    }
}
