<?php

namespace App\Http\Controllers;

use Netto\Http\Controllers\EmailVerificationPromptController as BaseController;

class EmailVerificationPromptController extends BaseController
{
    protected const VIEW = 'auth.verify';
    protected const DESTINATION = 'personal';
}
