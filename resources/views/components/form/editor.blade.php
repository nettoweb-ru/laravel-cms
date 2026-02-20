@props([
    'name',
    'id' => $name,
    'label' => '',
    'width' => 12,
    'required' => false,
    'messages' => [],
    'value' => '',
    'multilang' => false,
    'height' => 400,
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
                    <x-cms::form.partials.editor
                        :language="$langCode"
                        :height="$height"
                        :name="$name.'|'.$langCode"
                        :id="$id.'_'.$langCode"
                        :value="$langValue"
                        {{ $attributes }}
                    />
                </div>
            @endforeach
        @else
            <x-cms::form.partials.editor
                :height="$height"
                :name="$name"
                :id="$id"
                :value="$value"
                {{ $attributes }}
            />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.errors
        :messages="$messages"
        :multilang="$multilang"
    />
</div>
