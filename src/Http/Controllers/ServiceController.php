<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;
use Netto\Http\Requests\CookieRequest;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    /**
     * @param CookieRequest $request
     * @return JsonResponse
     */
    public function setCookie(CookieRequest $request): JsonResponse
    {
        set_admin_cookie($request->get('key'), $request->get('value'));
        return response()->json(true);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function transliterate(Request $request): JsonResponse
    {
        return response()->json([
            'string' => transliterate($request->get('string', '')),
        ]);
    }
}
