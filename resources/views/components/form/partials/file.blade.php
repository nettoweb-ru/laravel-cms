<div class="grid-value-file js-file-attr">
    <div class="cell filename">
        <input type="text" class="input text disabled js-file-text" value="{{ $value }}" disabled />
        <input type="hidden" name="{{ $name }}" class="js-file-value" value="{{ $value }}" />
        <input type="file" name="{{ $name }}_new" id="{{ $id }}" class="hidden js-file-input" />
    </div>
    <div class="cell icons">
        <x-cms::form.button type="button" bg="icons.upload" class="btn-icon btn-normal js-file-upload" title="{{ __('cms::main.title_upload_file_new') }}" />
        @if ($value)
            <x-cms::form.button type="button" bg="icons.download" class="btn-icon btn-normal js-file-download" data-filename="{{ DIRECTORY_SEPARATOR.$value }}" title="{{ __('cms::main.title_upload_file_download') }}" />
            <x-cms::form.button type="button" bg="icons.remove" class="btn-icon btn-warning js-file-delete" data-status="0" data-filename="{{ $value }}" title="{{ __('cms::main.title_upload_file_delete') }}" />
        @else
            <x-cms::form.button type="button" bg="icons.download" class="btn-icon btn-normal disabled" disabled />
            <x-cms::form.button type="button" bg="icons.remove" class="btn-icon btn-warning disabled" disabled />
        @endif
    </div>
</div>
