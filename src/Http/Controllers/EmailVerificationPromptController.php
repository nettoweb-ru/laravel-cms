<?php

namespace Netto\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    protected const VIEW = 'cms::auth.verify';
    protected const DESTINATION = 'admin.personal';

    /**
     * @param Request $request
     * @return RedirectResponse|View
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->route(static::DESTINATION)
            : view(static::VIEW, ['title' => __('cms::auth.action_verify_email')]);
    }
}
