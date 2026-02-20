@props([
    'name',
    'id' => $name,
    'disabled' => false,
    'autofocus' => false,
    'multiple' => false,
    'options' => [],
    'value' => '',
])

@php
    $class = 'input text';
    if ($multiple) {
        $value = array_filter((array) $value);
        $name .= '[]';
    }
@endphp

<select
    @if ($multiple)
        multiple
    @endif
    @if ($disabled)
        disabled
        @if ($multiple)
            id="{{ $id }}"
        @endif
        {!! $attributes->merge(['class' => $class.' disabled'])->toHtml() !!}
    @else
        name={{ $name }}
        id="{{ $id }}"
        {!! $attributes->merge(['class' => $class])->toHtml() !!}
    @endif
    @if ($autofocus)
        autofocus
    @endif
>
    @foreach ($options as $k => $v)
        @if (is_array($v))
            <optgroup label="{{ $k }}">
                @foreach ($v as $k2 => $v2)
                    <option value="{{ $k2 }}" @if (($multiple && in_array($k2, $value)) || ($k2 == $value)) selected @endif >{!! $v2 !!}</option>
                @endforeach
            </optgroup>
        @else
            <option value="{{ $k }}" @if (($multiple && in_array($k, $value)) || ($k == $value)) selected @endif >{!! $v !!}</option>
        @endif
    @endforeach
</select>

@if ($disabled)
    @if ($multiple)
        @if ($value)
            @foreach ($value as $item)
                <input type="hidden" name="{{ $name }}" value="{{ $item }}"/>
            @endforeach
        @else
            <input type="hidden" name="{{ $name }}" value="" />
        @endif
    @else
        <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}" />
    @endif
@endif
