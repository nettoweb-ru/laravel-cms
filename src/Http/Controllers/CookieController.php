<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\JsonResponse;

use Netto\Http\Requests\CookieRequest;
use Netto\Services\CmsService;

use App\Http\Controllers\Controller;

class CookieController extends Controller
{
    /**
     * @param CookieRequest $request
     * @return JsonResponse
     */
    public function set(CookieRequest $request): JsonResponse
    {
        CmsService::setAdminCookie($request->get('key'), $request->get('value'));
        return response()->json(true);
    }
}
