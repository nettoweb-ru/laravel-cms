<?php

declare(strict_types=1);

namespace Netto\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Cache, Log};
use Netto\Models\Redirect;

abstract class RedirectService
{
    public static function getCanonicalDomain(Request $request): string
    {
        $return = (config('cms.redirects.https') ? 'https' : 'http') . '://';

        if (config('redirects.use_www')) {
            $return .= 'www.';
        }

        return $return . self::getBaseDomain($request->getHttpHost());
    }

    public static function getCanonicalUrl(Request $request): string
    {
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

        return self::getCanonicalDomain($request) . $path;
    }

    public static function getRequestedDomain(Request $request): string
    {
        return ($request->isSecure() ? 'https' : 'http') . '://' . $request->getHttpHost();
    }

    public static function getRequestedUrl(Request $request): string
    {
        $uri = $request->getRequestUri();

        if ($uri == '/') {
            $uri = '';
        }

        return self::getRequestedDomain($request) . $uri;
    }

    public static function getRedirect(Request $request): ?RedirectResponse
    {
        $path = '/' . trim($request->path(), '/');
        $redirects = self::getCachedRedirects();

        foreach ($redirects['static'] as $redirect) {
            if ($redirect['source'] == $path) {
                return self::redirect(
                    self::getRequestedUrl($request),
                    self::getCanonicalDomain($request) . $redirect['destination'],
                    $request->ip(),
                    $redirect['status']
                );
            }
        }

        foreach ($redirects['dynamic'] as $redirect) {
            preg_match('/^' . str_replace(['/', '?', '='], ['\\/', '\\?', '\\='], $redirect['source']) . '$/', $path, $results);
            unset($results[0]);

            if ($results) {
                $to = $redirect['destination'];

                foreach ($results as $key => $value) {
                    $to = str_replace("\${$key}", $value, $to);
                }

                return self::redirect(
                    self::getRequestedUrl($request),
                    self::getCanonicalDomain($request) . $to,
                    $request->ip(),
                    $redirect['status']
                );
            }
        }

        return null;
    }

    public static function redirect(string $source, string $destination, string $ip, int $status = 301): RedirectResponse
    {
        if (in_array($status, config('cms.logs.track', []))) {
            if (empty($destination)) {
                $destination = '/';
            }

            Log::channel($status)->info("[".$ip."]".chr(9).chr(9).chr(9)."{$source} → {$destination}");
        }

        return redirect()->intended($destination, $status);
    }

    protected static function getBaseDomain(string $domain): string
    {
        if (str_starts_with($domain, 'www.')) {
            $domain = substr($domain, 4);
        }

        return $domain;
    }

    protected static function getCachedRedirects(): array
    {
        return Cache::rememberForever('redirects', function () {
            $return = [
                'static' => [],
                'dynamic' => [],
            ];

            foreach (Redirect::query()->where('is_active', '=', '1')->get() as $item) {
                /** @var Redirect $item */
                $redirect = $item->toArray();

                if ($redirect['is_regexp']) {
                    $return['dynamic'][] = $redirect;
                } else {
                    $return['static'][] = $redirect;
                }
            }

            return $return;
        });
    }
}
