@props([
    'name',
    'id' => $name,
    'type' => 'text',
    'value' => '',
    'disabled' => false,
    'autofocus' => false,
    'autocomplete' => 'off',
    'transliterate' => false,
    'dir' => config('text_dir'),
])

@php
    $class = 'input text';
@endphp

@if ($disabled)
    <input type="{{ $type }}" id="{{ $id }}" value="{{ $value }}" dir="{{ $dir }}" disabled {!! $attributes->merge(['class' => 'input text disabled'])->toHtml() !!} autocomplete="off" />
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
@else
    <input type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ $value }}"
        autocomplete="{{ $autocomplete }}"
        dir="{{ $dir }}"
        @if ($autofocus) autofocus @endif
        @if ($transliterate)
            data-transliterate-code="{{ $transliterate }}"
            data-transliterate-last="{{ $value }}"
            {!! $attributes->merge(['class' => $class.' js-transliterate'])->toHtml() !!}
        @else
            {!! $attributes->merge(['class' => $class])->toHtml() !!}
        @endif
    />
@endif
