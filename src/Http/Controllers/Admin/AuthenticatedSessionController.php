<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\{RedirectResponse, Request, Response};
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;
use Netto\Http\Requests\Admin\LoginRequest;

class AuthenticatedSessionController extends BaseController
{
    /**
     * @return Response
     */
    public function create(): Response
    {
        $this->addTitle(__('auth.authentication'));

        return $this->view('cms::auth.login');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route(get_default_language_code().'.home');
    }

    /**
     * @param LoginRequest $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->route($this->getRouteAdmin('home'));
    }
}
