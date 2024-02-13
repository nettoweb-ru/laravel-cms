<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadController extends Controller
{
    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function __invoke(Request $request): BinaryFileResponse
    {
        $filename = $request->get('filename');
        if (empty($filename)) {
            abort(404);
        }

        $path = base_path().$filename;
        if (!File::exists($path)) {
            abort(404);
        }

        return response()->download($path);
    }
}
