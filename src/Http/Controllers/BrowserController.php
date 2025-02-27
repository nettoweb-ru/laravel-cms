<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BrowserController extends Abstract\AdminController
{
    private const STORAGE = 'public';

    /**
     * @return View
     */
    public function create(): View
    {
        $title = __('cms::main.general_browser');
        $this->crumbs[] = [
            'title' => $title,
       ];

        return $this->getView('cms::browser', [
            'header' => $title,
            'url' => route('admin.browser.list')
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->get('id', []);
        if (empty($id)) {
            abort(400);
        }

        $dirs = [];
        $files = [];

        $disk = Storage::disk(self::STORAGE);
        foreach ($id as $item) {
            if ($disk->directoryExists($item)) {
                $dirs[] = $item;
            } else if ($disk->exists($item)) {
                $files[] = $item;
            } else {
                abort(400);
            }
        }

        if (empty($dirs) && empty($files)) {
            abort(400);
        }

        foreach ($dirs as $dir) {
            $disk->deleteDirectory($dir);
        }

        foreach ($files as $file) {
            $disk->delete($file);
        }

        return response()->json(true);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function directory(Request $request): JsonResponse
    {
        $name = $request->get('name');
        if (empty($name)) {
            abort(400);
        }

        $dir = $request->get('dir', DIRECTORY_SEPARATOR);
        $disk = Storage::disk(self::STORAGE);

        $dirName = $dir.transliterate(trim($name, DIRECTORY_SEPARATOR));

        if ($disk->directoryExists($dirName)) {
            abort(400, __('cms::main.error_dir_exists'));
        }

        $disk->makeDirectory($dirName);
        return response()->json(true);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $params = $request->query();

        $dir = DIRECTORY_SEPARATOR;
        if (!empty($params['dir'])) {
            $dir .= ltrim($params['dir'], DIRECTORY_SEPARATOR);
        }

        $disk = Storage::disk(self::STORAGE);
        if (!$disk->directoryExists($dir)) {
            abort(400);
        }

        $dirs = [];
        foreach ($disk->directories($dir) as $item) {
            $dirs[] = [
                'dir' => 1,
                'name' => basename($item),
                'size' => '',
                'date' => $disk->lastModified($item),
            ];
        }
        $dirs = $this->getSortedList($dirs, $params);

        $files = [];
        foreach ($disk->files($dir) as $item) {
            $files[] = [
                'dir' => 0,
                'name' => basename($item),
                'size' => $disk->size($item),
                'date' => $disk->lastModified($item),
            ];
        }

        $files = $this->getSortedList($files, $params);

        $list = array_merge($dirs, $files);
        foreach ($list as $key => $value) {
            if (!$value['dir']) {
                $list[$key]['size'] = format_file_size($value['size']);
            }

            $list[$key]['date'] = format_date($value['date']);
        }

        $parent = null;
        $explode = array_filter(explode(DIRECTORY_SEPARATOR, $dir));

        if ($explode) {
            array_pop($explode);
            $parent = implode(DIRECTORY_SEPARATOR, $explode).DIRECTORY_SEPARATOR;
        }

        $return = [
            'results' => [
                'items' => $list,
                'dirCurrent' => $dir,
                'dirParent' => $parent,
            ],
        ];

        if ($params['init']) {
            $return['init'] = [
                'path' => get_storage_path(self::STORAGE),
                'url' => [
                    'delete' => route('admin.browser.delete'),
                    'upload' => route('admin.browser.upload'),
                    'directory' => route('admin.browser.directory'),
                ],
            ];
        }

        return response()->json($return);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $dir = $request->get('dir', DIRECTORY_SEPARATOR);
        $file = $request->file('file');
        $disk = Storage::disk(self::STORAGE);

        if (empty($file)) {
            abort(400);
        }

        if ($file->getError()) {
            abort(400, $file->getErrorMessage());
        }

        $originalName = $file->getClientOriginalName();
        $ext = pathinfo($originalName, PATHINFO_EXTENSION);
        $name = transliterate(str_replace('.'.$ext, '', $originalName)).($ext ? '.'.$ext : '');

        if ($disk->exists($dir.$name)) {
            abort(400, __('cms::main.error_file_exists'));
        }

        /** @var UploadedFile $file */
        $file->storeAs(trim($dir, DIRECTORY_SEPARATOR), $name, self::STORAGE);
        return response()->json(true);
    }

    /**
     * @param array $list
     * @param array $params
     * @return array
     */
    private function getSortedList(array $list, array $params): array
    {
        $sort = [];
        foreach ($list as $key => $item) {
            $sort[$key] = $item[$params['sort']];
        }

        if ($params['sortDir'] == 'asc') {
            asort($sort);
        } else {
            arsort($sort);
        }

        $sorted = [];
        foreach ($sort as $key => $value) {
            $sorted[] = $list[$key];
        }

        return $sorted;
    }
}
