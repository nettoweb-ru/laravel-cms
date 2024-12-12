<x-cms::layout.admin :title="$title" :chain="$chain">
@pushonce('head')
    @vite([
        'resources/css/netto/browser.css',
        'resources/js/netto/list.widget.js',
        'resources/js/netto/browser.js',
    ])
@endpushonce
    <p>
        <span class="text-big header js-title">{{ $header }}</span>
    </p>
    <div class="browser js-browser" data-url="{{ $url }}" data-dir="{{ DIRECTORY_SEPARATOR }}" data-upload-max-filesize="{{ ini_parse_quantity(ini_get('upload_max_filesize')) }}" data-post-max-size="{{ ini_parse_quantity(ini_get('post_max_size')) }}">
        <div class="block header">
            <div class="table table-header">
                <div class="cell cell-path" dir="ltr">
                    <x-cms::form.string name="" id="path" type="text" class="js-path" disabled />
                </div>
                <div class="cell cell-buttons">
                    <x-cms::form.button type="button" bg="icons.up" class="btn-icon btn-normal js-icon-folder-up disabled" disabled title="{{ __('cms::main.title_folder_up') }}" />
                    <x-cms::form.button type="button" bg="icons.add-folder" class="btn-icon btn-normal js-icon-folder-add disabled" disabled title="{{ __('cms::main.title_folder_new') }}" data-message="{{ __('cms::main.general_browser_dir_prompt') }}" />
                    <x-cms::form.button type="button" bg="icons.upload" class="btn-icon btn-normal js-icon-upload disabled" disabled title="{{ __('cms::main.title_upload') }}" />
                    <x-cms::form.button type="button" bg="icons.invert-selection" class="btn-icon btn-normal js-icon-invert disabled" disabled title="{{ __('cms::main.title_invert') }}" />
                    <x-cms::form.button type="button" bg="icons.remove" class="btn-icon btn-warning js-icon-delete disabled" disabled title="{{ __('cms::main.title_delete') }}" />
                </div>
            </div>
        </div>
        <div class="block main">
            <div class="browser-layer content js-results">
                <div class="results data js-results-found">
                    <div class="files">
                        <table class="info">
                            <thead>
                            <tr class="js-head">
                                <th class="col-8 sortable" data-code="name"><span class="text-small">{{ __('cms::main.general_browser_name') }}</span></th>
                                <th class="col-2 size sortable" data-code="size"><span class="text-small">{{ __('cms::main.general_browser_size') }}</span></th>
                                <th class="col-2 sortable" data-code="date"><span class="text-small">{{ __('cms::main.general_browser_date') }}</span></th>
                            </tr>
                            </thead>
                            <tbody class="js-body"></tbody>
                        </table>
                    </div>
                </div>
                <div class="results empty js-results-empty">
                    <span class="text">{{ __('cms::main.general_browser_empty_dir') }}</span>
                </div>
            </div>
            <div class="browser-layer animation js-animation">
                <div class="table table-animation">
                    <div class="cell">
                        <div class="loading"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                    </div>
                </div>
            </div>
        </div>
        <input type="file" class="hidden js-upload" />
    </div>
</x-cms::layout.admin>
