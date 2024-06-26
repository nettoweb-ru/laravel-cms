@props([
    'name',
    'value' => '',
    'width' => 12,
    'required' => false,
    'disabled' => false,
    'multilang' => false,
    'id' => $name,
    'label' => '',
    'messages' => [],
    'autocomplete' => 'off',
    'transliterate' => false,
])

<div class="grid-cols-{{ $width }}">
    <x-cms::form.partials.label :id="$id" :text="$label" :required="$required" />
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="js-multilang hidden" data-code="{{ $langCode }}">
                    <input name="{{ $name }}|{{ $langCode }}" value="{{ $langValue }}" id="{{ $id }}_{{ $langCode }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'input text'.($disabled ? ' disabled' : '').($transliterate ? ' js-transliterate' : '')]) !!} autocomplete="{{ $autocomplete }}" @if ($transliterate) data-transliterate-code="{{ $transliterate }}" @endif />
                </div>
            @endforeach
        @else
            <input name="{{ $name }}" value="{{ $value }}" id="{{ $id }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'input text'.($disabled ? ' disabled' : '').($transliterate ? ' js-transliterate' : '')]) !!} autocomplete="{{ $autocomplete }}" @if ($transliterate) data-transliterate-code="{{ $transliterate }}" @endif />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.partials.errors :messages="$messages" :multilang="$multilang" />
</div>
