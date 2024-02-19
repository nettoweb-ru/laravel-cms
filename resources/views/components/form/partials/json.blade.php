<div class="table json-value-table js-json" data-name="{{ $name }}">
    <div class="cell json-value-cell control">
        <x-cms::form.button bg="icons.plus-sign" class="btn-icon btn-normal js-json-add" type="button" title="{{ __('cms::main.title_add_value') }}" />
    </div>
    <div class="cell json-value-cell items js-json-values">
        @foreach ($value as $key => $item)
            <div class="json-value-item">
                <input type="text" class="input text js-json-value" name="{{ $name }}[]" id="{{ $id }}_{{ $key }}" value="{{ $item }}" />
            </div>
        @endforeach
    </div>
</div>
