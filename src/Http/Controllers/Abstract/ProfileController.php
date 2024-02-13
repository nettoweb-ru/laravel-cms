<?php
namespace Netto\Http\Controllers\Abstract;

use App\Http\Controllers\Controller;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

abstract class ProfileController extends Controller
{
    protected const ROUTE_EDIT = '';
    protected const VIEW = '';

    protected const DESTINATION = '/';

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to(static::DESTINATION);
    }

    /**
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        return view(static::VIEW, [
            'object' => $request->user(),
            'title' => __('cms::auth.profile')
        ]);
    }

    /**
     * @param $request
     * @return RedirectResponse
     */
    protected function _update($request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route(static::ROUTE_EDIT)->with('status', __('cms::auth.profile_updated'));
    }
}
