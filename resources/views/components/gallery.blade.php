@pushonce('head')
    @vite([
        'resources/css/netto/gallery.css',
        'resources/js/netto/list.widget.js',
        'resources/js/netto/gallery.js',
        'resources/js/netto/jquery.longpress.js',
    ])
@endpushonce

<div class="list js-gallery" data-url="{{ $url }}">
    <div class="list-block top">
        <div class="table list-top">
            <div class="cell list-top title">
                <span class="text-big header js-title"></span>
            </div>
            <div class="cell list-top actions">
                <x-cms::form.button type="button" bg="icons.create" class="btn-icon btn-normal js-icon-create js-link disabled" disabled data-url="" title="{{ __('cms::main.title_create') }}" />
                <x-cms::form.button type="button" bg="icons.invert-selection" class="btn-icon btn-normal js-icon-invert disabled" disabled title="{{ __('cms::main.title_invert') }}" />
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
                <div class="list-results-data js-images">

                </div>
                <div class="list-results-bottom">
                    <div class="table list-bottom">
                        <div class="cell list-bottom total">
                            <div class="list-total-block">
                                <span class="text">{{ __('cms::main.general_list_total') }}:</span>
                            </div>
                            <div class="list-total-block">
                                <span class="text js-counter-items">0</span>
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
