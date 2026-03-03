@props([
    'text',
    'id',
    'required',
    'dynamic' => false,
])

@php
    $labelTag = $id && !$dynamic;
@endphp

@if ($text)
    <div class="grid-item label">
        @if ($labelTag)
            <label for="{{ $id }}">
        @endif

        <span class="text-small @if ($required) required @endif">{{ $text }}</span>

        @if ($labelTag)
            </label>
        @endif
    </div>
@endif
