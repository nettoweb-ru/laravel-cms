<?php

declare(strict_types=1);

namespace Netto\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest as BaseRequest;
use Netto\Models\Redirect;
use Netto\Rules\UniqueSlug;

class RedirectRequest extends BaseRequest
{
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'source' => ['required', 'string', 'max:255', new UniqueSlug(Redirect::class, $this->get('id'))],
            'destination' => ['required_unless:status,'.implode(',', config('cms.redirects.statuses.error')), 'nullable', 'string', 'max:255'],
            'is_active' => ['in:1,0'],
            'is_regexp' => ['in:1,0'],
            'status' => ['integer', 'in:' . implode(',', array_merge(config('cms.redirects.statuses.redirect'), config('cms.redirects.statuses.error')))],
        ];
    }
}
