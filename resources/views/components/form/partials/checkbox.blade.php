@props([
    'options',
    'type',
    'id',
    'name',
    'disabled',
    'multiple',
    'value',
])

@foreach ($options as $k => $v)
    <div class="grid-value-option">
        <div class="table value-option">
            <div class="cell value">
                <input type="{{ $type }}" id="{{ $id }}_{{ $k }}" value="{{ $k }}" name="{{ $name }}@if ($multiple)[]@endif" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'input text'.($disabled ? ' disabled' : '')]) !!} @if (($multiple && in_array($k, $value)) || (!$multiple && ($k == $value))) checked @endif />
            </div>
            <div class="cell label">
                <label for="{{ $id }}_{{ $k }}">
                    <span class="text">{{ $v }}</span>
                </label>
            </div>
        </div>
    </div>
@endforeach
