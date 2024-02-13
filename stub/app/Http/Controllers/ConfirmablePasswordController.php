<?php

namespace App\Http\Controllers;

use Netto\Http\Controllers\ConfirmablePasswordController as BaseController;

class ConfirmablePasswordController extends BaseController
{
    protected const VIEW = 'auth.confirm';
    protected const DESTINATION = 'personal';
}
