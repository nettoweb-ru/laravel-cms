@props([
    'name',
    'id' => $name,
    'value' => '',
    'disabled' => false,
    'autofocus' => false,
    'dir' => config('text_dir'),
])

<textarea
    dir="{{ $dir }}"
    @if ($disabled)
        id="{{ $id }}-visible"
        {!! $attributes->merge(['class' => 'input text disabled'])->toHtml() !!}
    @else
        @if ($autofocus) autofocus @endif
        id="{{ $id }}"
        name="{{ $name }}"
        {!! $attributes->merge(['class' => 'input text'])->toHtml() !!}
    @endif
>{{ $value }}</textarea>

@if ($disabled)
    <input type="hidden" name="{{ $name }}" value="{{ $value }}" id="{{ $id }}" />
@endif
