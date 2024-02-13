<?php

namespace App\Http\Controllers;

use Netto\Http\Controllers\EmailVerificationNotificationController as BaseController;

class EmailVerificationNotificationController extends BaseController
{
    protected const DESTINATION = 'personal';
}
