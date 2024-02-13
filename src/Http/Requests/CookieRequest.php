<?php
namespace Netto\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CookieRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'key' => ['required', 'string', 'max:255'],
            'value' => ['required', 'string', 'max:255'],
        ];
    }
}
