<?php

namespace Netto\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Netto\Http\Requests\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    protected const VIEW = 'cms::auth.login';
    protected const DESTINATION = 'admin.home';

    /**
     * @return View
     */
    public function create(): View
    {
        return view(static::VIEW, [
            'title' => __('cms::auth.authentication'),
        ]);
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

        return redirect()->route(static::DESTINATION);
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

        return redirect('/');
    }
}
