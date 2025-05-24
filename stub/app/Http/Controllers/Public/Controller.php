<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Public\Abstract\Controller as BaseController;
use Illuminate\Http\Response;

class Controller extends BaseController
{
    /**
     * @return Response
     */
    public function home(): Response
    {
        return $this->view('home', $this->getPublicationData('home'));
    }
}
