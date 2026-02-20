@pushonce('head')
    @vite([
        'resources/css/netto/form.scss',
        'resources/js/netto/form.js',
    ])
@endpushonce

@props([
    'id' => 'form',
    'url',
    'method' => 'post',
    'objectId' => null,
    'sheets' => [1 => ''],
    'conditions' => [],
    'apply' => true,
    'save' => true,
    'buttons' => '',
    'multiLang' => false,
    'languages' => [],
])

@php
    $visible = [];
    foreach ($sheets as $key => $value) {
        if (!array_key_exists($key, $conditions) || $conditions[$key]) {
            $visible[$key] = $value;
        }
    }

    $multiple = (count($visible) > 1);
@endphp

<div class="form js-form" data-id="{{ $id }}" data-multilang="{{ (int) $multiLang }}">
    <form method="post" action="{{ $url['save'] }}" enctype="multipart/form-data">
        @csrf
        @method($method)

        @if (!is_null($objectId))
            <input type="hidden" name="id" value="{{ $objectId }}" />
        @endif

        <div class="form-block sheets">
            @foreach ($visible as $key => $value)
                <div class="sheet js-form-sheet" data-id="{{ $key }}">
                    <div class="table sheet-padding-table">
                        <div class="cell sheet-padding-cell @if (!$multiple) single @endif">
                            <div class="form-grid">
                                {!! ${"sheet{$key}"} !!}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="form-block controls @if (!$multiple) margin @endif">
            <div class="table">
                @if ($multiple)
                    @foreach ($visible as $key => $value)
                        <div class="cell title js-form-sheet-switch" data-id="{{ $key }}">
                            <span class="text-small mobile" title="{{ __($value) }}">{{ $loop->iteration }}</span>
                            <span class="text-small desktop">{{ __($value) }}</span>
                        </div>

                        @if (!$loop->last)
                            <div class="cell space"></div>
                        @endif
                    @endforeach
                @endif

                <div class="cell wide"></div>

                @if ($multiLang)
                    @foreach ($languages as $language)
                        <div class="cell title lang js-form-lang-switch" data-id="{{ $language['slug'] }}">
                            <span class="text-small mobile">{{ $language['slug'] }}</span>
                            <span class="text-small desktop">{{ $language['name'] }}</span>
                        </div>

                        @if (!$loop->last)
                            <div class="cell space"></div>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>

        <div class="form-block buttons">
            @if ($save)
                <button class="btn btn-label btn-green btn-bg cloud-done">{{ __('main.action_save') }}</button>
            @endif

            @if ($apply)
                <button class="btn btn-label btn-blue btn-bg done" name="button_apply" value="1">{{ __('main.action_apply') }}</button>
            @endif

            @if (!empty($url['index']))
                <button type="button" class="btn btn-label btn-blue btn-bg unavailable js-link" data-url="{{ $url['index'] }}">{{ __('main.action_cancel') }}</button>
            @endif

            @if (!empty($url['destroy']))
                <button type="button" class="btn btn-label btn-red btn-bg remove js-item-destroy">{{ __('main.action_delete') }}</button>
            @endif

            {!! $buttons !!}
        </div>
    </form>

    @if (!empty($url['destroy']))
        <form method="post" action="{{ $url['destroy'] }}" class="js-form-destroy">
            @csrf
            @method('delete')
        </form>
    @endif
</div>
