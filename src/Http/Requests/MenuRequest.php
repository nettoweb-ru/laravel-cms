<?php
namespace Netto\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Netto\Models\Language;
use Netto\Models\Menu;
use Netto\Models\MenuItem;
use Netto\Rules\UniqueSlug;

class MenuRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'lang_id' => ['required', 'integer', 'exists:'.Language::class.',id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/', new UniqueSlug(Menu::class, $this->get('id'), $this->get('lang_id'))],
            'menu_item_id' => ['nullable', 'exists:'.MenuItem::class.',id'],
        ];
    }
}
