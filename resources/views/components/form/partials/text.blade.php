@props([
    'name',
    'id' => $name,
    'value' => '',
    'disabled' => false,
    'autofocus' => false,
])

@php
    $class = 'input text textarea';
@endphp

<textarea
    @if ($disabled)
        id="{{ $id }}-visible"
        {!! $attributes->merge(['class' => $class.' disabled'])->toHtml() !!}
    @else
        @if ($autofocus) autofocus @endif
        id="{{ $id }}"
        name="{{ $name }}"
        {!! $attributes->merge(['class' => $class])->toHtml() !!}
    @endif
>{{ $value }}</textarea>

@if ($disabled)
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" id="{{ $id }}" />
@endif
