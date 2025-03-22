<?php
namespace Netto\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageRequest extends FormRequest
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
            get_rules_multilang([
                'caption' => ['nullable', 'string', 'max:255'],
            ]),
            [
                'sort' => ['integer', 'min:0', 'max:16777215'],
            ]
        );
    }
}
