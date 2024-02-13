@props([
    'name',
    'value' => '',
    'width' => 12,
    'required' => false,
    'disabled' => false,
    'multilang' => false,
    'id' => $name,
    'label' => '',
    'messages' => [],
    'autocomplete' => 'off',
    'value' => '',
])

<div class="grid-cols-{{ $width }}">
    <x-cms::form.partials.label :id="$id" :text="$label" :required="$required" />
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="js-multilang hidden" data-code="{{ $langCode }}">
                    <textarea name="{{ $name }}|{{ $langCode }}" id="{{ $id }}_{{ $langCode }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'textarea text'.($disabled ? ' disabled' : '')]) !!}>{{ $langValue }}</textarea>
                </div>
            @endforeach
        @else
            <textarea name="{{ $name }}" id="{{ $id }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'textarea text'.($disabled ? ' disabled' : '')]) !!}>{{ $value }}</textarea>
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.partials.errors :messages="$messages" :multilang="$multilang" />
</div>
