@props([
    'name',
    'id' => $name,
    'label' => '',
    'width' => 12,
    'required' => false,
    'disabled' => false,
    'autofocus' => false,
    'multiple' => false,
    'options' => [],
    'messages' => [],
    'value' => '',
    'multilang' => false,
])

<div class="grid-cols grid-cols-{{ $width }}">
    <x-cms::form.partials.label
        :id="$multilang ? '' : $id"
        :text="$label"
        :required="$required"
    />
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="js-multilang hidden" data-code="{{ $langCode }}">
                    <x-cms::form.partials.select
                        :name="$name.'|'.$langCode"
                        :id="$id.'_'.$langCode"
                        :value="$langValue"
                        :disabled="$disabled"
                        :autofocus="$autofocus"
                        :multiple="$multiple"
                        :options="$options[$langCode]"
                        {{ $attributes }}
                    />
                </div>
            @endforeach
        @else
            <x-cms::form.partials.select
                :name="$name"
                :id="$id"
                :value="$value"
                :disabled="$disabled"
                :autofocus="$autofocus"
                :multiple="$multiple"
                :options="$options"
                {{ $attributes }}
            />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.errors
        :messages="$messages"
        :multilang="$multilang"
    />
</div>
