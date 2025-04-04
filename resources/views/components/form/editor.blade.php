@props([
    'name',
    'value' => '',
    'width' => 12,
    'height' => 400,
    'required' => false,
    'id' => $name,
    'multilang' => false,
    'label' => '',
    'messages' => [],
    'lang' => '',
])

@pushonce('head')
    @vite([
        'resources/css/styles.css',
        'resources/js/styles.js',
    ])
    @php load_cdn_resources('ckeditor') @endphp
    <link rel="stylesheet" href="{{ asset('assets/css/editor.css') }}">
@endpushonce

<div class="grid-cols-{{ $width }}">
    <x-cms::form.partials.label :id="$id" :text="$label" :required="$required" />
    <x-cms::form.partials.value>
        @if ($multilang)
            @foreach ($value as $langCode => $langValue)
                <div class="js-multilang hidden" data-code="{{ $langCode }}">
                    <x-cms::form.partials.editor :language="$langCode" :height="$height" :id="$id.'_'.$langCode" :name="$name.'|'.$langCode" :value="$langValue"  />
                </div>
            @endforeach
        @else
            <x-cms::form.partials.editor name="{{ $name }}" :language="$lang" :height="$height" :id="$id" :name="$name" :value="$value"  />
        @endif
    </x-cms::form.partials.value>
    <x-cms::form.partials.errors :messages="$messages" :multilang="$multilang" />
</div>
