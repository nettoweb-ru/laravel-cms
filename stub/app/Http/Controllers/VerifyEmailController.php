<?php

namespace App\Http\Controllers;

use Netto\Http\Controllers\VerifyEmailController as BaseController;

class VerifyEmailController extends BaseController
{
    protected const DESTINATION = 'personal';
}
