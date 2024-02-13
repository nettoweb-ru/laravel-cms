<?php
namespace Netto\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Netto\Models\Album;
use Netto\Models\Language;
use Netto\Models\Publication;
use Netto\Rules\UniqueSlug;

class PublicationRequest extends FormRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        $classLanguage = Language::class;
        $classAlbum = Album::class;

        return [
            'lang_id' => ['required', 'integer', 'exists:'.$classLanguage.',id'],
            'album_id' => ['nullable', 'integer', 'exists:'.$classAlbum.',id'],
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
