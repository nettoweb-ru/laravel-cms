@props([
    'name',
    'multilang',
    'required',
    'disabled',
    'multiple',
    'options',
    'value',
    'id',
])

<select name="{{ $name }}@if ($multiple)[]@endif" id="{{ $id }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'select text'.($disabled ? ' disabled' : '')]) !!} @if ($multiple) multiple @endif>
    @foreach ($options as $k => $v)
        @if (is_array($v))
            <optgroup label="{{ $k }}">
                @foreach ($v as $k2 => $v2)
                    <option value="{{ $k2 }}" @if (($multiple && in_array($k2, $value)) || (!$multiple && ($k2 == $value))) selected @endif >{!! $v2 !!}</option>
                @endforeach
            </optgroup>
        @else
            <option value="{{ $k }}" @if (($multiple && in_array($k, $value)) || (!$multiple && ($k == $value))) selected @endif >{!! $v !!}</option>
        @endif
    @endforeach
</select>
