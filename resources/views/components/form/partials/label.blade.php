@props([
    'id' => '',
    'text' => '',
    'required' => false,
    'dynamic' => false,
])

@if (!empty($text))
    <div class="grid-item label">
        @if ($id && !$dynamic)
            <label for="{{ $id }}">
        @endif

        <span class="text-small">{{ $text }}@if ($required)<span class="required">*</span>@endif</span>

        @if ($id && !$dynamic)
            </label>
        @endif
    </div>
@endif
