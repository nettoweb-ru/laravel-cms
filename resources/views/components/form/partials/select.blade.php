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
])

@if ($dynamic)
    @pushonce('head')
        <script src="//code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
        <script>
            autocomplete['{{ $name }}'] = [
                @foreach ($options as $k => $v)
                    @if (is_array($v))
                        @foreach ($v as $k2 => $v2)
                        {label: '{{ $v2 }}', value: '{{ $k2 }}'},
                        @endforeach
                    @else
                        {label: '{{ $v }}', value: '{{ $k }}'},
                    @endif
                @endforeach
            ]
        </script>
    @endpushonce
    <div class="grid-item-autocomplete js-autocomplete" data-multiple="{{ (int) $multiple }}" data-name="{{ $name }}">
        <div class="autocomplete-input">
            <label for="{{ $name }}-autocomplete">
                <input id="{{ $name }}-autocomplete" class="input text js-autocomplete-input" placeholder="{{ __('cms::main.general_autocomplete_prompt') }}" @if (!$multiple) value="{{ is_null($value) ? '' : $options[$value] }}" @endif/>
            </label>
        </div>
        @if ($multiple)
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
