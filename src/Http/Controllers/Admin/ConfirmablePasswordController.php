<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\{RedirectResponse, Request, Response};
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;

class ConfirmablePasswordController extends BaseController
{
    /**
     * @return Response
     */
    public function show(): Response
    {
        $header = __('auth.action_confirm_password');
        $this->addTitle($header);

        return $this->view('cms::auth.confirm', [
            'btnTitle' => $header
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());
        return redirect()->route($this->getRouteAdmin('home'));
    }
}
