<?php

namespace Netto\View\Components;

use Illuminate\View\{Component, View};

class Captcha extends Component
{
    public array $errors;

    public int $valueShow;
    public string $valueText;

    private int $max;

    /**
     * @param array $errors
     * @param int $max
     */
    public function __construct(array $errors = [], int $max = 999)
    {
        $this->errors = $errors;
        $this->max = $max;
    }

    /**
     * @param int $valueShow
     * @param mixed $value
     * @return bool
     */
    public function check(int $valueShow, mixed $value): bool
    {
        return (($valueShow / config('cms.captcha.multiplier')) - config('cms.captcha.bias')) === (int) $value;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        $this->init();
        return view('cms::components.captcha');
    }

    /**
     * @return void
     */
    private function init(): void
    {
        $value = mt_rand(1, $this->max);

        $this->valueShow = ($value + config('cms.captcha.bias')) * config('cms.captcha.multiplier');
        $this->valueText = spell_number($value);
    }
}
