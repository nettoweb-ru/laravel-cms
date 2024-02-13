@props([
    'name',
    'multilang' => false,
    'width' => 12,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'id' => $name,
    'label' => '',
    'messages' => [],
    'options' => [],
    'value' => [],
])

<div class="grid-cols-{{ $width }}">
    <x-cms::form.partials.label :id="$id" :text="$label" :required="$required" />
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="js-multilang hidden" data-code="{{ $langCode }}">
                    <x-cms::form.partials.select :name="$name.'['.$langCode.']'" :multiple="$multiple" :disabled="$disabled" :options="$options[$langCode]" :value="$langValue" :id="$id.'_'.$langCode" />
                </div>
            @endforeach
        @else
            <x-cms::form.partials.select :name="$name" :multiple="$multiple" :disabled="$disabled" :options="$options" :value="$value" :id="$id" />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.partials.errors :messages="$messages" />
</div>
