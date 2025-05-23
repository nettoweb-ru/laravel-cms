<?php
namespace Netto\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UserBalanceRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'value' => ['decimal:0,2', 'min:-999999.99', 'max:999999.99'],
        ];
    }
}
