<?php

namespace Netto\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Captcha implements ValidationRule
{
    private int $value;

    /**
     * @param int $value
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $captcha = new \Netto\View\Components\Captcha();
        if (!$captcha->check($this->value, $value)) {
            $fail(__('captcha.wrong_number'));
        }
    }
}
