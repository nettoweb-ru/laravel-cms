<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\{RedirectResponse, Request, Response};
use Illuminate\Support\Facades\{Hash, Password};
use Illuminate\Validation\Rules\Password as PasswordDefaults;
use Illuminate\Support\Str;

use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;

class NewPasswordController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $header = __('auth.action_reset_password');
        $this->addTitle($header);

        return $this->view('cms::auth.reset', [
            'request' => $request,
            'btnTitle' => $header,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordDefaults::defaults()],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        return $status == Password::PASSWORD_RESET
            ? redirect()->route($this->getRouteAdmin('login'))->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
