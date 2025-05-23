<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Netto\Http\Controllers\Admin\Abstract\BrowserController as BaseController;

class PublicDiskController extends BaseController
{
    protected string $disk = 'public';

    /**
     * @return Response
     */
    public function index(): Response
    {
        $header = __('main.general_browser');
        $this->addCrumb($header);
        $this->addTitle($header);

        return $this->view('cms::browser', [
            'header' => $header,
            'disk' => $this->disk,
        ]);
    }
}
