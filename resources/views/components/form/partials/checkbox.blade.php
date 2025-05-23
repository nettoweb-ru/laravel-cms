@props([
    'name',
    'value',
    'id' => $name,
    'disabled' => false,
    'options' => [],
    'multiple' => false,
    'type' => null,
])

@php
    $class = 'input';

    if ($multiple) {
        $name .= '[]';
        $type = 'checkbox';
    } else if (is_null($type)) {
        $type = 'radio';
    }
@endphp

@foreach ($options as $key => $option)
    @php
        $checked = ($multiple && in_array($key, $value)) || ($key == $value);
    @endphp
    <div class="grid-value-option @if ($disabled) disabled @endif">
        <div class="table value-option">
            <div class="cell value">
                <input type="{{ $type }}"
                       id="{{ $id }}_{{ $key }}"
                       @if ($checked) checked @endif
                       @if ($disabled) disabled
                       @else
                           value="{{ $key }}" name="{{ $name }}"
                       @endif
                       @if ($disabled)
                           {!! $attributes->merge(['class' => $class.' disabled'])->toHtml() !!}
                       @else
                           {!! $attributes->merge(['class' => $class])->toHtml() !!}
                       @endif
                />
                @if ($disabled && $checked)
                    <input type="hidden" name="{{ $name }}" value="{{ $key }}" />
                @endif
            </div>
            <div class="cell label">
                <label for="{{ $id }}_{{ $key }}">
                    <span class="text">{{ $option }}</span>
                </label>
            </div>
        </div>
    </div>
@endforeach
