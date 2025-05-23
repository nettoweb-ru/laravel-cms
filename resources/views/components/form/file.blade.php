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
])

<div class="grid-cols grid-cols-{{ $width }}">
    <x-cms::form.partials.label
        :id="$multilang || $disabled ? '' : $id"
        :text="$label"
        :required="$required"
    />
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="js-multilang hidden" data-code="{{ $langCode }}">
                    <x-cms::form.partials.file
                        :name="$name.'|'.$langCode"
                        :id="$id.'_'.$langCode"
                        :value="$langValue"
                        :disabled="$disabled"
                        {{ $attributes }}
                    />
                </div>
            @endforeach
        @else
            <x-cms::form.partials.file
                :name="$name"
                :id="$id"
                :value="$value"
                :disabled="$disabled"
                {{ $attributes }}
            />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.errors
        :messages="$messages"
        :multilang="$multilang"
    />
</div>
