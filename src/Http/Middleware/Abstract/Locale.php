<?php

namespace Netto\Http\Middleware\Abstract;

use Symfony\Component\HttpFoundation\Response;

abstract class Locale
{
    /**
     * @param Response $response
     * @param string $language
     * @return Response
     */
    protected function setContentHeader(Response $response, string $language): Response
    {
        if (method_exists($response, 'header')) {
            /** @var \Illuminate\Http\Response $response */
            $response->header('Content-Language', $language);
        }

        return $response;
    }
}
