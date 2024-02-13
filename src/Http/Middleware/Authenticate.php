<?php

namespace Netto\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('admin.login');
    }
}
