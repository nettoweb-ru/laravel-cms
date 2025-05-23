<?php

namespace Netto\Http\Controllers\Admin;

use Illuminate\Support\Facades\{Auth, File};
use Illuminate\Http\{JsonResponse, Request, Response};
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use App\Models\User;

use Netto\Http\Controllers\Admin\Abstract\Controller as BaseController;

class HelperController extends BaseController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function cookie(Request $request): JsonResponse
    {
        set_admin_cookie($request->get('key'), $request->get('value'));
        return response()->json(true);
    }

    /**
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function download(Request $request): BinaryFileResponse
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

    /**
     * @return Response
     */
    public function home(): Response
    {
        /** @var User $user */
        $user = Auth::user();
        $this->addTitle(__('main.general_home'));

        return $this->view('cms::home', [
            'data' => [
                __('main.params_application') => [
                    __('main.attr_name') => config('app.name'),
                    __('main.params_application_url') => '<a href="'.config('app.url').'" target="_blank">'.config('app.url').'</a>',
                ],
                __('main.params_general') => [
                    __('main.params_user_name') => $user->getAttribute('name'),
                    __('main.attr_language') => app()->getLocale(),
                    __('main.attr_locale') => config('locale_full'),
                    __('main.params_user_timezone') => config('app.timezone'),
                    __('main.params_php_upload_max_filesize').' (upload_max_filesize)' => format_file_size(ini_parse_quantity(ini_get('upload_max_filesize'))),
                    __('main.params_php_post_max_size').' (post_max_size)' => format_file_size(ini_parse_quantity(ini_get('post_max_size'))),
                    __('main.params_php_max_input_vars').' (max_input_vars)' => format_number(ini_get('max_input_vars')),
                ],
                __('main.params_versions') => get_versions(),
            ],
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function transliterate(Request $request): JsonResponse
    {
        return response()->json([
            'string' => transliterate($request->get('string', '')),
        ]);
    }
}
