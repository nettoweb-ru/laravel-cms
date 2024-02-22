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
                    <x-cms::form.partials.checkbox :type="$type" :id="$id.'_'.$langCode" :value="$langValue" :disabled="$disabled" :options="$options[$langCode]" :name="$name.'|'.$langCode" :multiple="$multiple" />
                </div>
            @endforeach
        @else
            <x-cms::form.partials.checkbox :type="$type" :id="$id" :options="$options" :value="$value" :disabled="$disabled"  :name="$name" :multiple="$multiple" />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.partials.errors :messages="$messages" :multilang="$multilang" />
</div>
