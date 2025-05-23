<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;

class VerifyEmailController extends BaseController
{
    /**
     * @param EmailVerificationRequest $request
     * @return RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $path = route($this->getRouteAdmin('home')).'?verified=1';

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended($path);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended($path);
    }
}
