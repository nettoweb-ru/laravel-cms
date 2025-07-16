@props([
    'name',
    'id' => $name,
    'disabled' => false,
    'autofocus' => false,
    'multiple' => false,
    'options' => [],
    'value' => '',
    'custom' => false,
])

@if ($multiple)
    @php
        $value = array_filter((array) $value);
        $name .= '[]';
    @endphp
@endif

@if (!$disabled)
    @php \Netto\Services\CDNService::load('jquery.ui') @endphp
    @push('head')
<script>
    autocomplete['{{ $name }}'] = [
@foreach ($options as $k => $v)
        {label: '{!! escape_quotes($v) !!}', value: '{!! escape_quotes($k) !!}'},
@endforeach
    ]
</script>
    @endpush
@endif

<div class="@if (!$disabled) js-autocomplete @endif" data-multiple="{{ (int) $multiple }}" data-name="{{ $name }}" data-custom-value="{{ (int) $custom }}">
    <div class="autocomplete-input">
        <label for="{{ $id }}">
            <input type="text"
                value="@if (!$multiple && $value){{ $options[$value] ?? $value }}@endif"
                id="{{ $id }}"
                placeholder="…"
                @if ($disabled) disabled @endif
                @if ($autofocus) autofocus @endif
                @if ($disabled)
                {!! $attributes->merge(['class' => 'input text disabled'])->toHtml() !!}
                @else
                {!! $attributes->merge(['class' => 'input text js-autocomplete-input'])->toHtml() !!}
                @endif
            />
        </label>
    </div>
    @if ($multiple)
        <div class="autocomplete-value js-autocomplete-multiple-hold">
            @if ($value)
                @foreach ($value as $item)
                    <div class="value-item js-autocomplete-multiple @if ($disabled) disabled @endif" data-id="{{ $item }}">
                        <div class="value-item-table">
                            <div class="value-item-cell">
                                <span class="text">{{ $options[$item] }}</span>
                                <input type="hidden" name="{{ $name }}" value="{{ $item }}"/>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <input type="hidden" name="{{ $name }}" class="js-autocomplete-multiple-hidden" value=""/>
            @endif
        </div>
    @else
        <input type="hidden" class="js-autocomplete-single" name="{{ $name }}" value="{{ $value }}" />
    @endif
</div>
