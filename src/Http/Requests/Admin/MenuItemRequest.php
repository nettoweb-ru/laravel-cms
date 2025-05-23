<?php

namespace Netto\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;

class MenuItemRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'max:255', 'regex:/^[a-z0-9\-]+$/'],
            'link' => ['nullable', 'max:255'],
            'is_active' => ['in:1,0'],
            'is_blank' => ['in:1,0'],
            'sort' => ['integer', 'min:0', 'max:65535'],
            'highlight' => ['nullable', 'array'],
        ];
    }
}
