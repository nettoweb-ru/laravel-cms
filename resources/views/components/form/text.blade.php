@props([
    'name',
    'id' => $name,
    'label' => '',
    'width' => 12,
    'required' => false,
    'disabled' => false,
    'autofocus' => false,
    'messages' => [],
    'value' => '',
    'multilang' => false,
    'dir' => config('text_dir'),
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
                <div class="multilang js-multilang hidden" data-code="{{ $langCode }}">
                    <x-cms::form.partials.text
                        :name="$name.'|'.$langCode"
                        :id="$id.'_'.$langCode"
                        :value="$langValue"
                        :disabled="$disabled"
                        :autofocus="$autofocus"
                        :dir="$dir"
                        {{ $attributes }}
                    />
                </div>
            @endforeach
        @else
            <x-cms::form.partials.text
                :name="$name"
                :id="$id"
                :value="$value"
                :disabled="$disabled"
                :autofocus="$autofocus"
                :dir="$dir"
                {{ $attributes }}
            />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.errors
        :messages="$messages"
        :multilang="$multilang"
    />
</div>
