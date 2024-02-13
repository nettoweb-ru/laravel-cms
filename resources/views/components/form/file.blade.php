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
    'storage' => 'public',
])

<div class="grid-cols-{{ $width }}">
    <x-cms::form.partials.label :id="$id" :text="$label" :required="$required" />
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="js-multilang hidden" data-code="{{ $langCode }}">
                    <div class="grid-value-file">
                        <x-cms::form.partials.file :name="$name.'|'.$langCode" :value="$langValue" :storage="$storage" :id="$id.'_'.$langCode" />
                    </div>
                </div>
            @endforeach
        @else
            <x-cms::form.partials.file :name="$name" :value="$value" :storage="$storage" :id="$id" />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.partials.errors :messages="$messages" :multilang="$multilang" />
</div>
