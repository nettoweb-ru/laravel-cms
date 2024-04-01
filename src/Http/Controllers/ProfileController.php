<?php

namespace Netto\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\ProfileUpdateRequest as WorkRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Abstract\ProfileController
{
    protected const ROUTE_EDIT = 'admin.profile.edit';
    protected const VIEW = 'cms::auth.profile';

    /**
     * @param Request $request
     * @return View
     */
    public function edit(Request $request): View
    {
        $header = __('cms::auth.profile');

        return view(static::VIEW, [
            'object' => $request->user(),
            'title' => implode(config('cms.title_separator', ' | '), [$header, __('cms::main.general_management')]),
            'header' => $header,
            'tabs' => [
                'profile_tab' => 1,
            ],
        ]);
    }

    /**
     * @param WorkRequest $request
     * @return RedirectResponse
     */
    public function update(WorkRequest $request): RedirectResponse
    {
        return $this->_update($request);
    }
}
