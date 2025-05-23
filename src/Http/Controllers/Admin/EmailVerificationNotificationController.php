<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\{RedirectResponse, Request};

use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;

class EmailVerificationNotificationController extends BaseController
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route($this->getRouteAdmin('home'));
        }

        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', __('auth.verification_link_sent'));
    }
}
