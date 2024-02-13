<?php
namespace Netto\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Netto\Models\Image;

class ImageRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $object = new Image();

        return array_merge(
            $object->getUploadRules([
                'filename' => ['required', 'mimes:jpg,png,gif,webp'],
            ]),
            $object->getMultiLangRules([
                'caption' => ['nullable', 'string', 'max:255'],
            ]),
            [
                'sort' => ['integer', 'min:0', 'max:16777215'],
            ]
        );
    }
}
