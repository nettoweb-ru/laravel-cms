<?php

namespace Netto\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueSlug implements ValidationRule
{
    private string $className;
    private ?int $id;
    private ?int $langId = null;

    /**
     * @param string $className
     * @param string|null $id
     * @param string|null $langId
     */
    public function __construct(string $className, ?string $id, ?string $langId = null)
    {
        $this->className = $className;

        if (!is_null($langId)) {
            $this->langId = (int) $langId;
        }


        $this->id = (int) $id;
        if ($this->id === 0) {
            $this->id = null;
        }
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $object = new $this->className();
        $builder = $object::select('id')->where($attribute, $value)->whereNot('id', $this->id);

        if (!is_null($this->langId)) {
            $builder->where('lang_id', $this->langId);
        }

        if (count($builder->get())) {
            $fail(__('validation.unique'));
        }
    }
}
