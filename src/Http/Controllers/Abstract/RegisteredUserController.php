<?php
namespace Netto\Http\Controllers\Abstract;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Netto\Models\Role;

abstract class RegisteredUserController extends Controller
{
    protected const VIEW = '';
    protected const DESTINATION = '';
    protected const ROLES = [];

    /**
     * @return View
     */
    public function create(): View
    {
        return view(static::VIEW, ['title' => __('cms::auth.action_register')]);
    }

    /**
     * @param $request
     * @return RedirectResponse
     */
    protected function _store($request): RedirectResponse
    {
        $user = User::create(array_merge($request->validated(), [
            'password' => Hash::make($request->password),
        ]));

        if (static::ROLES && ($roleId = Role::select('id')->whereIn('slug', static::ROLES)->pluck('id'))) {
            $user->roles()->sync($roleId);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route(static::DESTINATION);
    }
}
