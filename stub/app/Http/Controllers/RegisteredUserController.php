<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Netto\Http\Controllers\Abstract\RegisteredUserController as BaseController;
use App\Http\Requests\RegisterRequest as WorkRequest;

class RegisteredUserController extends BaseController
{
    protected const VIEW = 'auth.register';
    protected const DESTINATION = 'personal';
    protected const ROLES = [];

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function store(WorkRequest $request): RedirectResponse
    {
        return $this->_store($request);
    }
}
