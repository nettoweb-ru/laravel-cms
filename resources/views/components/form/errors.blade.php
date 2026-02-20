@props([
    'multilang' => false,
    'messages' => [],
])

@if ($multilang)
    @foreach ($messages as $langCode => $langMessages)
        <div class="multilang js-multilang hidden" data-code="{{ $langCode }}">
            <x-cms::form.partials.errors :messages="$langMessages" />
        </div>
    @endforeach
@else
    <x-cms::form.partials.errors :messages="$messages" />
@endif
