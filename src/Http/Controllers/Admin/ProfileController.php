<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\{RedirectResponse, Request, Response};
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\Abstract\Controller as BaseController;
use Netto\Http\Requests\Admin\ProfileUpdateRequest as WorkRequest;

class ProfileController extends BaseController
{
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

        return redirect()->route(get_default_language_code().'.home');
    }

    /**
     * @return Response
     */
    public function edit(): Response
    {
        $header = __('auth.profile');
        $this->addCrumb($header);
        $this->addTitle($header);

        return $this->view('cms::auth.profile', [
            'header' => $header,
            'object' => Auth::getUser(),
        ]);
    }

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function update(WorkRequest $request): RedirectResponse
    {
        /** @var FormRequest $request */
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->route($this->getRouteAdmin('profile.edit'))->with('status', __('auth.profile_updated'));
    }
}
