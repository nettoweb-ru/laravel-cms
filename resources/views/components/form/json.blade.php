@props([
    'name',
    'value' => [],
    'width' => 12,
    'required' => false,
    'disabled' => false,
    'multilang' => false,
    'id' => $name,
    'label' => '',
    'messages' => [],
])

<div class="grid-cols-{{ $width }}">
    <x-cms::form.partials.label :id="$id" :text="$label" :required="$required" />
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="js-multilang hidden" data-name="{{ $name.'|'.$langCode }}" data-code="{{ $langCode }}">
                    <x-cms::form.partials.json :name="$name.'|'.$langCode" :disabled="$disabled" :value="$langValue" :id="$id.'_'.$langCode" />
                </div>
            @endforeach
        @else
            <x-cms::form.partials.json :name="$name" :disabled="$disabled" :value="$value" :id="$id" />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.partials.errors :messages="$messages" :multilang="$multilang" />
</div>
