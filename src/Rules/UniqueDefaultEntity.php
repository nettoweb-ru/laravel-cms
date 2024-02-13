<?php

namespace Netto\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;

class UniqueDefaultEntity implements ValidationRule
{
    private string $className;
    private ?int $id;
    private bool $value;

    /**
     * @param string $className
     * @param string|null $id
     * @param string $value
     */
    public function __construct(string $className, ?string $id, string $value)
    {
        $this->className = $className;
        $this->value = (intval($value) === 1);

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
        if (!$this->value) {
            /** @var Builder $object */
            $object = new $this->className();
            if (count($object->where('is_default', '1')->whereNot('id', $this->id)->get()) === 0) {
                $fail(__('validation.default_entity_required'));
            }
        }
    }
}
