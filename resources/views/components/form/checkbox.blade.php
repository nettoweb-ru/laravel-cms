@props([
    'name',
    'width' => 12,
    'required' => false,
    'disabled' => false,
    'multilang' => false,
    'id' => $name,
    'label' => '',
    'messages' => [],
    'options',
    'multiple' => false,
    'value' => [],
    'type' => 'checkbox',
])
<div class="grid-cols-{{ $width }}">
    @if ($label)
        <x-cms::form.partials.label :text="$label" :required="$required" />
    @endif
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="js-multilang hidden" data-code="{{ $langCode }}">
                    @foreach ($options[$langCode] as $k => $v)
                        <div class="grid-value-option">
                            <div class="table value-option">
                                <div class="cell value">
                                    <input type="{{ $type }}" id="{{ $id }}_{{ $langCode }}_{{ $k }}" value="{{ $k }}" name="{{ $name }}[{{ $langCode }}]@if ($multiple)[]@endif" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'input text'.($disabled ? ' disabled' : '')]) !!} @if (($multiple && in_array($k, $langValue)) || (!$multiple && ($k == $langValue))) checked @endif />
                                </div>
                                <div class="cell label">
                                    <label for="{{ $id }}_{{ $langCode }}_{{ $k }}">
                                        <span class="text">{{ $v }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @else
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
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.partials.errors :messages="$messages" />
</div>
