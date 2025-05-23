<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\{RedirectResponse, Request, Response};

use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;

class EmailVerificationPromptController extends BaseController
{
    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function __invoke(Request $request): RedirectResponse|Response
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route($this->getRouteAdmin('home'));
        }

        $header = __('auth.action_verify_email');
        $this->addTitle($header);

        return $this->view('cms::auth.verify', [
            'btnTitle' => $header,
        ]);
    }
}
