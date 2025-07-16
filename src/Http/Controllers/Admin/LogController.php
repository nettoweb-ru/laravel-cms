<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use Illuminate\Http\{JsonResponse, Request, Response};

use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;
use Netto\Services\ReadLogService;

class LogController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $filename = $request->get('filename');
        if (empty($filename)) {
            abort(400);
        }

        $path = storage_path('logs/'.$filename);
        if (!file_exists($path)) {
            abort(400);
        }

        File::delete($path);
        return response()->json($path);
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $header = __('main.list_logs');
        $this->addCrumb($header);
        $this->addTitle($header);

        return $this->view('cms::logs', [
            'header' => $header,
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $items = [];
        $total = 0;

        if ($files = config('cms.logs.read.files', [])) {
            $items = (new ReadLogService(
                $files,
                config('cms.logs.read.max', 10)
            ))->read();
            $total = count($items);
        }

        return response()->json([
            'items' => view('cms::components.log-data', [
                'items' => $items
            ])->render(),
            'total' => $total,
        ]);
    }
}
