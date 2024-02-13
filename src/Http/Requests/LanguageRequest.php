<?php
namespace Netto\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Netto\Models\Language;
use Netto\Rules\UniqueDefaultEntity;

class LanguageRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'sort' => ['integer', 'min:0', 'max:16777215'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'size:2', 'lowercase', 'alpha:ascii', Rule::unique(Language::class, 'slug')->ignore($this->get('id'))],
            'locale' => ['required', 'string', 'size:5', 'regex:/^[a-z]{2}_[A-Z]{2}$/'],
            'is_default' => ['in:1,0', new UniqueDefaultEntity(Language::class, $this->get('id'), $this->get('is_default'))],
        ];
    }
}
