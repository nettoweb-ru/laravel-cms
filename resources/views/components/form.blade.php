@pushonce('head')
    @vite([
        'resources/css/netto/form.css',
        'resources/js/netto/form.js',
    ])
@endpushonce

@props(['id' => '', 'url', 'method', 'objectId' => null, 'sheets' => [1 => ''], 'conditions'  => [], 'apply' => true, 'save' => true, 'buttons' => ''])

<div class="js-form" data-id="{{ $id }}" data-multilang="{{ (int) $multiLang }}" data-upload-max-filesize="{{ ini_parse_quantity(ini_get('upload_max_filesize')) }}" data-post-max-size="{{ ini_parse_quantity(ini_get('post_max_size')) }}">
    <form method="post" action="{{ $url['save'] }}" enctype="multipart/form-data">
        @csrf
        @method($method)

        @if ($objectId)
            <input type="hidden" name="id" value="{{ $objectId }}"/>
        @endif

        <div class="form">
            <div class="form-block form-sheets">
                @foreach ($sheets as $key => $value)
                    @if (array_key_exists($key, $conditions) && !$conditions[$key])
                        @continue
                    @endif
                    <div class="form-sheet js-form-sheet" data-id="{{ $key }}">
                        <div class="table sheet-padding">
                            <div class="cell sheet-padding">
                                <div class="form-grid">
                                    {!! ${"sheet{$key}"} !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="form-block form-sheet-nav">
                <div class="table sheet-nav">
                    @if (count($sheets) > 1)
                        @foreach ($sheets as $key => $value)
                            @if (!array_key_exists($key, $conditions) || $conditions[$key])
                                <div class="cell sheet-nav-cell txt label js-form-sheet-switch" data-id="{{ $key }}">
                                    <span class="text-small">{{ __($value) }}</span>
                                </div>
                                @if (!$loop->last)
                                    <div class="cell sheet-nav-cell space">
                                        <span class="text"></span>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                    @endif

                    <div class="cell sheet-nav-cell wide"></div>

                    @if ($multiLang)
                        @foreach ($languages as $language)
                            <div class="cell sheet-nav-cell txt lang js-form-lang-switch" data-id="{{ $language['slug'] }}">
                                <span class="text-small default">{{ $language['slug'] }}</span>
                                <span class="text-small mobile">{{ $language['name'] }}</span>
                            </div>
                            @if (!$loop->last)
                                <div class="cell sheet-nav-cell space">
                                    <span class="text"></span>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="form-block form-buttons">
                @if ($save)
                    <x-cms::form.button bg="icons.cloud-done" class="btn-form btn-success" name="button_save" value="1">{{ __('main.action_save') }}</x-cms::form.button>
                @endif
                @if ($apply)
                    <x-cms::form.button bg="icons.done" class="btn-form btn-normal" name="button_apply" value="1">{{ __('main.action_apply') }}</x-cms::form.button>
                @endif
                @if (!empty($url['index']))
                    <x-cms::form.button bg="icons.unavailable" class="btn-form btn-normal js-link" type="button" data-url="{{ $url['index'] }}">{{ __('main.action_cancel') }}</x-cms::form.button>
                @endif
                @if (!empty($url['destroy']))
                    <x-cms::form.button bg="icons.remove" class="btn-form btn-warning js-item-destroy" type="button">{{ __('main.action_delete') }}</x-cms::form.button>
                @endif
                {!! $buttons !!}
            </div>
        </div>
    </form>
    @if (!empty($url['destroy']))
        <form method="post" class="js-form-destroy" action="{{ $url['destroy'] }}">
            @csrf
            @method('delete')
        </form>
    @endif
</div>
