<?php

namespace Netto\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;

class NavigationRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'sort' => ['integer', 'min:0', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'is_active' => ['in:1,0'],
            'highlight' => ['nullable', 'array'],
        ];
    }
}
