<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\{RedirectResponse, Request, Response};
use Illuminate\Support\Facades\Password;

use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;

class PasswordResetLinkController extends BaseController
{
    /**
     * @return Response
     */
    public function create(): Response
    {
        $header = __('auth.action_get_email_link');
        $this->addTitle($header);

        return $this->view('cms::auth.remind', [
            'btnTitle' => $header
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
