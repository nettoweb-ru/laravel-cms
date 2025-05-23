<?php

namespace Netto\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;
use Netto\Models\{Album, Language, Publication};
use Netto\Rules\UniqueSlug;

class PublicationRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'lang_id' => ['required', 'integer', 'exists:'.Language::class.',id'],
            'album_id' => ['nullable', 'integer', 'exists:'.Album::class.',id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9\-]+$/', new UniqueSlug(Publication::class, $this->get('id'), $this->get('lang_id'))],
            'meta_title' => ['nullable', 'max:255'],
            'meta_keywords' => ['nullable'],
            'meta_description' => ['nullable'],
            'og_title' => ['nullable', 'max:255'],
            'og_description' => ['nullable'],
            'content' => ['nullable'],
        ];
    }
}
