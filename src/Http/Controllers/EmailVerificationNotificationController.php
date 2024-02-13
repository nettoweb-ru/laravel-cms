<?php

namespace Netto\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    protected const DESTINATION = 'admin.home';

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route(static::DESTINATION);
        }

        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', __('cms::auth.verification_link_sent'));
    }
}
