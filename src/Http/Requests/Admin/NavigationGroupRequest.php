<?php

namespace Netto\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;

class NavigationGroupRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'sort' => ['integer', 'min:0', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
