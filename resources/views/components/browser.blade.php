@pushonce('head')
    @vite([
        'resources/js/netto/browser.js',
    ])
@endpushonce

@props([
    'class' => 'browser js-browser',
    'id' => 'browser',
    'url',
    'root',
    'dir' => '',
    'actions' => [],
])

<x-cms::ajaxlist :id="$id" :url="$url" :class="$class">
    <x-slot:head>
        <x-cms::form.string class="js-path" name="" id="{{ $id }}_path" type="text" disabled />
    </x-slot:head>

    <x-slot:buttons>
        <button class="btn btn-bg btn-blue up js-list-button" data-type="up" title="{{ __('main.title_folder_up') }}"></button>
        @if (!empty($actions['directory']))
            <button class="btn btn-bg btn-blue add-folder js-list-button" data-type="directory" data-url="{{ $actions['directory'] }}" title="{{ __('main.title_folder_new') }}" data-message="{{ __('main.general_browser_dir_prompt') }}"></button>
        @endif
        @if (!empty($actions['upload']))
            <button class="btn btn-bg btn-blue upload js-list-button" data-type="upload" data-url="{{ $actions['upload'] }}" title="{{ __('main.title_upload') }}"></button>
            <input type="file" class="hidden js-upload" />
        @endif
        <button class="btn btn-bg btn-blue invert-selection js-list-button" data-type="invert" title="{{ __('main.title_invert') }}"></button>
        @if (!empty($actions['delete']))
            <button class="btn btn-bg btn-red remove js-list-button" data-type="delete" data-url="{{ $actions['delete'] }}" title="{{ __('main.title_delete') }}"></button>
        @endif
    </x-slot:buttons>

    <div class="browser js-browser-hold" data-root="{{ $root }}" data-start-dir="{{ $dir }}" data-upload-max-filesize="{{ ini_parse_quantity(ini_get('upload_max_filesize')) }}" data-post-max-size="{{ ini_parse_quantity(ini_get('post_max_size')) }}">
        <table class="info">
            <thead class="js-head">
            <tr>
                <th class="col-8 sortable" data-code="name"><span class="text-small">{{ __('main.general_browser_name') }}</span></th>
                <th class="col-2 size sortable" data-code="size"><span class="text-small">{{ __('main.general_browser_size') }}</span></th>
                <th class="col-2 sortable" data-code="date"><span class="text-small">{{ __('main.general_browser_date') }}</span></th>
            </tr>
            </thead>
            <tbody class="js-body"></tbody>
        </table>
    </div>

</x-cms::ajaxlist>
