<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Controllers\Abstract\ProfileController as BaseController;
use App\Http\Requests\ProfileUpdateRequest as WorkRequest;

class ProfileController extends BaseController
{
    protected const ROUTE_EDIT = 'profile.edit';
    protected const VIEW = 'auth.profile';

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function update(WorkRequest $request): RedirectResponse
    {
        return $this->_update($request);
    }
}
