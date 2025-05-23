<?php

namespace Netto\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;

class ImageRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return array_merge(
            get_rules_upload([
                'filename' => ['required', 'mimes:jpg,png,gif,webp'],
            ]),
            get_rules_multilingual([
                'caption' => ['nullable', 'string', 'max:255'],
            ]),
            [
                'sort' => ['integer', 'min:0', 'max:65535'],
            ]
        );
    }
}
