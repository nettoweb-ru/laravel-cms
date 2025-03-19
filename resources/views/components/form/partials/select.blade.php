@props([
    'name',
    'multilang',
    'required',
    'disabled',
    'multiple',
    'options',
    'value',
    'id',
    'dynamic',
    'allowCustomValue',
])

@if ($dynamic)
    @push('head')
        <script>
            autocomplete['{{ $name }}'] = [
                    @foreach ($options as $k => $v)
                    @if (is_array($v))
                    @foreach ($v as $k2 => $v2)
                {label: '{!! escape_quotes($v2) !!}', value: '{!! escape_quotes($k2) !!}'},
                    @endforeach
                    @else
                {label: '{!! escape_quotes($v) !!}', value: '{!! escape_quotes($k) !!}'},
                @endif
                @endforeach
            ]
        </script>
    @endpush

    <div class="grid-item-autocomplete js-autocomplete" data-multiple="{{ (int) $multiple }}" data-name="{{ $name }}" data-custom-value="{{ (int) $allowCustomValue }}">
        <div class="autocomplete-input">
            <label for="{{ $id }}-autocomplete">
                <input id="{{ $id }}-autocomplete" class="input text js-autocomplete-input" placeholder="…" @if (!$multiple) value="{{ is_null($value) ? '' : $options[$value] }}" @endif/>
            </label>
        </div>
        @if ($multiple)
            @php
                $value = array_filter((array) $value);
            @endphp
            <div class="autocomplete-value js-autocomplete-multiple-hold">
                @if ($value)
                    @foreach ($value as $item)
                        <div class="value-item js-autocomplete-multiple" data-id="{{ $item }}">
                            <div class="value-item-table">
                                <div class="value-item-cell">
                                    <span class="text-small">{{ $options[$item] }}</span>
                                    <input type="hidden" name="{{ $name }}[]" value="{{ $item }}"/>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <input type="hidden" name="{{ $name }}[]" class="js-autocomplete-multiple-hidden" value=""/>
                @endif
            </div>
        @else
            <input type="hidden" class="js-autocomplete-single" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}"/>
        @endif
    </div>
@else
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
@endif
