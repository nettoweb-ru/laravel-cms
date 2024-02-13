@props([
    'name',
    'value' => '',
    'multilang' => false,
    'width' => 12,
    'required' => false,
    'disabled' => false,
    'id' => $name,
    'label' => '',
    'messages' => [],
    'autocomplete' => 'off',
])

<div class="grid-cols-{{ $width }}">
    <x-cms::form.partials.label :id="$id" :text="$label" :required="$required" />
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="js-multilang hidden" data-code="{{ $langCode }}">
                    <input name="{{ $name }}[{{ $langCode }}]" value="{{ $langValue }}" type="date" id="{{ $id }}_{{ $langCode }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'input text'.($disabled ? ' disabled' : '')]) !!} autocomplete="{{ $autocomplete }}" />
                </div>
            @endforeach
        @else
            <input name="{{ $name }}" value="{{ $value }}" type="date" id="{{ $id }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'input text'.($disabled ? ' disabled' : '')]) !!} autocomplete="{{ $autocomplete }}" />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.partials.errors :messages="$messages" />
</div>
