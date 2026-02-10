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

                    return self::redirect($request, $uri, $destination, $status);
                }
            } else if ($source == $uri) {
                if (is_null($destination)) {
                    abort($status);
                }

                return self::redirect($request, $uri, $destination, $status);
            }
        }

        return null;
    }

    /**
     * Get canonical hostname.
     *
     * @param Request $request
     * @return string
     */
    public static function getHostCanonical(Request $request): string
    {
        static $return;

        if (is_null($return)) {
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
        }

        return $return;
    }

    /**
     * Get requested hostname.
     *
     * @param Request $request
     * @return string
     */
    public static function getHostRequested(Request $request): string
    {
        static $return;

        if (is_null($return)) {
            $return = ($request->isSecure() ? 'https' : 'http').'://'.$request->getHttpHost();
        }

        return $return;
    }

    /**
     * Perform and track redirect.
     *
     * @param Request $request
     * @param string $source
     * @param string $destination
     * @param int $status
     * @return RedirectResponse|null
     */
    public static function redirect(Request $request, string $source, string $destination, int $status = 301): ?RedirectResponse
    {
        if (in_array($status, config('cms.logs.track', []))) {
            if (empty($destination)) {
                $destination = '/';
            }

            Log::channel($status)->info("[".$request->ip()."]".chr(9).chr(9).chr(9).self::getHostRequested($request).$source." → ".$destination);
        }

        return redirect()->intended(self::getHostCanonical($request).$destination, $status);
    }
}
