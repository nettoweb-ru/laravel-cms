<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;

class PasswordController extends BaseController
{
    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', __('auth.password_updated'));
    }
}
