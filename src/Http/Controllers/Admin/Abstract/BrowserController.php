<?php

namespace Netto\Http\Controllers\Admin\Abstract;

use App\Http\Controllers\Admin\Abstract\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\{JsonResponse, Request, UploadedFile};

abstract class BrowserController extends BaseController
{
    protected string $disk;

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

        $disk = Storage::disk($this->disk);
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
        if ($dir !== DIRECTORY_SEPARATOR) {
            $dir = DIRECTORY_SEPARATOR.trim($dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        }

        $dirName = $dir.transliterate(trim($name, DIRECTORY_SEPARATOR));

        $disk = Storage::disk($this->disk);
        if ($disk->directoryExists($dirName)) {
            abort(400, __('main.error_dir_exists'));
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
        $dir = $request->get('dir', DIRECTORY_SEPARATOR);
        if ($dir !== DIRECTORY_SEPARATOR) {
            $dir = DIRECTORY_SEPARATOR.trim($dir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        }

        $sort = $request->get('sort', 'id');
        $sortDir = $request->get('sortDir', 'asc');

        $disk = Storage::disk($this->disk);

        if (!$disk->directoryExists($dir)) {
            abort(400);
        }

        $dirs = [];
        foreach ($disk->directories($dir) as $item) {
            $dirs[] = [
                'name' => basename($item),
                'dir' => true,
                'size' => '',
                'date' => $disk->lastModified($item),
            ];
        }
        $dirs = $this->sort($dirs, $sort, $sortDir);

        $files = [];
        foreach ($disk->files($dir) as $item) {
            $files[] = [
                'name' => basename($item),
                'dir' => false,
                'size' => $disk->size($item),
                'date' => $disk->lastModified($item),
            ];
        }
        $files = $this->sort($files, $sort, $sortDir);

        $items = array_merge($dirs, $files);
        foreach ($items as $key => $value) {
            if (!$value['dir']) {
                $items[$key]['size'] = format_file_size($value['size']);
            }

            $items[$key]['date'] = format_date($value['date']);
        }

        $parent = null;
        if ($explode = array_filter(explode(DIRECTORY_SEPARATOR, $dir))) {
            array_pop($explode);
            $parent = implode(DIRECTORY_SEPARATOR, $explode).DIRECTORY_SEPARATOR;
        }

        return response()->json([
            'items' => $items,
            'total' => count($items),
            'currentDir' => $dir,
            'parentDir' => $parent,
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function upload(Request $request): JsonResponse
    {
        $dir = $request->get('dir', DIRECTORY_SEPARATOR);
        $file = $request->file('file');
        $disk = Storage::disk($this->disk);

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
            abort(400, __('main.error_file_exists'));
        }

        /** @var UploadedFile $file */
        $file->storeAs(trim($dir, DIRECTORY_SEPARATOR), $name, $this->disk);
        return response()->json(true);
    }

    /**
     * @param array $items
     * @param string $sort
     * @param string $sortDir
     * @return array
     */
    protected function sort(array $items, string $sort, string $sortDir): array
    {
        $array = [];
        foreach ($items as $key => $item) {
            $array[$key] = $item[$sort];
        }

        if ($sortDir == 'asc') {
            asort($array);
        } else {
            arsort($array);
        }

        $sorted = [];
        foreach ($array as $key => $value) {
            $sorted[] = $items[$key];
        }

        return $sorted;
    }
}
