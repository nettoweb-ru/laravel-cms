<?php
namespace Netto\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
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
            'sort' => ['integer', 'min:0', 'max:16777215'],
        ];
    }
}
