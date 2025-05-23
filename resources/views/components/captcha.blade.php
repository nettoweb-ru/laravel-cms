@props([
    'valueText',
    'valueShow',
    'errors',
])

<x-cms::form.string name="captcha" maxlength="3" placeholder="{{ $valueText }}"
    :label="__('captcha.enter_number')"
    :messages="$errors"
    :required="true"
/>
<input type="hidden" name="captcha_value" id="captcha_value" value="{{ $valueShow }}" />
