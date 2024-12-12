@pushonce('head')
    @vite([
        'resources/css/netto/list.css',
        'resources/js/netto/list.widget.js',
        'resources/js/netto/list.js',
    ])
@endpushonce

<div class="list js-list" data-url="{{ $url }}" data-id="{{ $id }}">
    <div class="list-block top">
        <div class="table list-top">
            <div class="cell list-top title">
                <span class="text-big header js-title"></span>
            </div>
            <div class="cell list-top actions">
                <x-cms::form.button type="button" bg="icons.create" class="btn-icon btn-normal js-icon-create js-link disabled hidden" disabled data-url="" title="{{ __('cms::main.title_create') }}" />
                <x-cms::form.button type="button" bg="icons.search" class="btn-icon btn-normal js-icon-find hidden disabled" disabled title="{{ __('cms::main.title_find') }}" />
                <x-cms::form.button type="button" bg="icons.download" class="btn-icon btn-normal js-icon-download hidden disabled" disabled title="{{ __('cms::main.title_download') }}" />
                <x-cms::form.button type="button" bg="icons.invert-selection" class="btn-icon btn-normal js-icon-invert disabled" disabled title="{{ __('cms::main.title_invert') }}" />
                <x-cms::form.button type="button" bg="icons.toggle-on" class="btn-icon btn-normal js-icon-toggle hidden disabled" disabled title="{{ __('cms::main.title_toggle') }}" />
                <x-cms::form.button type="button" bg="icons.remove" class="btn-icon btn-warning js-icon-delete disabled" disabled title="{{ __('cms::main.title_delete') }}" />
            </div>
        </div>
    </div>
    <div class="list-block content">
        <div class="list-content-layer animation js-animation">
            <div class="table">
                <div class="cell">
                    <div class="loading"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
                </div>
            </div>
        </div>
        <div class="list-content-layer results js-results">
            <div class="list-results found js-results-found">
                <div class="list-results-data">
                    <table>
                        <thead><tr class="js-head"></tr></thead>
                        <tbody class="js-body"></tbody>
                    </table>
                </div>
                <div class="list-results-bottom">
                    <div class="table list-bottom">
                        <div class="cell list-bottom per-page">
                            <label>
                                <select class="select narrow text js-per-page" name="per-page" title="{{ __('cms::main.title_per_page') }}">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="0">{{ __('cms::main.general_list_all') }}</option>
                                </select>
                            </label>
                        </div>
                        <div class="cell list-bottom total">
                            <div class="list-total-block">
                                <span class="text">{{ __('cms::main.general_list_total') }}:</span>
                            </div>
                            <div class="list-total-block">
                                <span class="text js-counter-items">0</span>
                            </div>
                        </div>
                        <div class="cell list-bottom navigation">
                            <div class="list-nav">
                                <div class="table list-nav">
                                    <div class="cell padding">
                                        <span class="text">{{ __('cms::main.general_list_page') }}</span>
                                    </div>
                                    <div class="cell">
                                        <label>
                                            <select class="select narrow text js-page disabled" disabled name="page"></select>
                                        </label>
                                    </div>
                                    <div class="cell padding">
                                        <span class="text">/</span>
                                    </div>
                                    <div class="cell">
                                        <span class="text js-counter-pages">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-results empty js-results-empty">
                <p class="text">
                    {{ __('cms::main.general_list_empty') }}
                </p>
            </div>
        </div>
    </div>
</div>
