@props([
    'name',
    'id' => $name,
    'disabled' => false,
    'value' => [],
])

<div class="grid-value-json table js-json" data-name="{{ $name }}">
    <div class="cell control">
        <button class="btn btn-bg btn-blue plus-sign @if ($disabled) disabled @else js-json-add @endif" @if ($disabled) disabled @endif type="button" title="{{ __('main.title_add_value') }}"></button>
    </div>
    <div class="cell values js-json-values">
        @foreach ($value as $key => $item)
            <div class="value">
                <x-cms::form.partials.string class="js-json-value" name="{{ $name }}[]" :value="$item" id="{{ $id }}_{{ $key }}" :disabled="$disabled" />
            </div>
        @endforeach
    </div>
</div>
