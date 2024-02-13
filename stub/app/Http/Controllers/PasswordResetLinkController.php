<?php

namespace App\Http\Controllers;

use Netto\Http\Controllers\PasswordResetLinkController as BaseController;

class PasswordResetLinkController extends BaseController
{
    protected const VIEW = 'auth.remind';
}
