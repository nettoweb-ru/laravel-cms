@props([
    'id' => '',
    'text' => '',
    'required' => false,
])

@if (!empty($text))
    <div class="grid-item label">
        @if ($id)
            <label for="{{ $id }}">
        @endif

        <span class="text-small">{{ $text }}@if ($required)<span class="required">*</span>@endif</span>

        @if ($id)
            </label>
        @endif
    </div>
@endif
