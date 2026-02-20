@props([
    'name',
    'id' => $name,
    'label' => '',
    'width' => 12,
    'required' => false,
    'disabled' => false,
    'messages' => [],
    'value' => '',
    'multilang' => false,
    'options' => [],
    'multiple' => false,
    'type' => null,
])

<div class="grid-cols grid-cols-{{ $width }}">
    <x-cms::form.partials.label
        id=""
        :text="$label"
        :required="$required"
    />
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="multilang js-multilang hidden" data-code="{{ $langCode }}">
                    <x-cms::form.partials.checkbox
                        :name="$name.'|'.$langCode"
                        :id="$id.'_'.$langCode"
                        :value="$langValue"
                        :disabled="$disabled"
                        :multiple="$multiple"
                        :options="$options[$langCode]"
                        :type="$type"
                        {{ $attributes }}
                    />
                </div>
            @endforeach
        @else
            <x-cms::form.partials.checkbox
                :name="$name"
                :id="$id"
                :value="$value"
                :disabled="$disabled"
                :multiple="$multiple"
                :options="$options"
                :type="$type"
                {{ $attributes }}
            />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.errors
        :messages="$messages"
        :multilang="$multilang"
    />
</div>
