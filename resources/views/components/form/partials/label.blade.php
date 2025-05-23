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

        <span class="text-small">{{ $text }}@if ($required)<span class="required">*</span>@endif</span>

        @if ($labelTag)
            </label>
        @endif
    </div>
@endif
