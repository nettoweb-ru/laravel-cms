@props([
    'name',
    'id' => $name,
    'disabled' => false,
    'value' => [],
])

<div class="table json-value-table js-json" data-name="{{ $name }}">
    <div class="cell json-value-cell control">
        <x-cms::form.button
            bg="icons.plus-sign"
            class="btn-icon btn-normal js-json-add"
            :disabled="$disabled"
            type="button"
            title="{{ __('main.title_add_value') }}"
        />
    </div>
    <div class="cell json-value-cell items js-json-values">
        @foreach ($value as $key => $item)
            <div class="json-value-item">
                <x-cms::form.partials.string class="js-json-value" name="{{ $name }}[]" :value="$item" id="{{ $id }}_{{ $key }}" :disabled="$disabled" />
            </div>
        @endforeach
    </div>
</div>
