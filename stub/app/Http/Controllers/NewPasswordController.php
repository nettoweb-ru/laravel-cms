<?php

namespace App\Http\Controllers;

use Netto\Http\Controllers\NewPasswordController as BaseController;

class NewPasswordController extends BaseController
{
    protected const DESTINATION = 'login';
    protected const VIEW = 'auth.reset';
}
