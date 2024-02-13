<?php

namespace App\Http\Controllers;

use Netto\Http\Controllers\AuthenticatedSessionController as BaseController;

class AuthenticatedSessionController extends BaseController
{
    protected const VIEW = 'auth.login';
    protected const DESTINATION = 'personal';
}
