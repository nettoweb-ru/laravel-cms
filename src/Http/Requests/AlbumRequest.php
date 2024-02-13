<?php
namespace Netto\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlbumRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'sort' => ['integer', 'min:0', 'max:16777215'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
