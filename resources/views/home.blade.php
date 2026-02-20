@pushonce('head')
    @vite([
        'resources/css/netto/home.scss',
    ])
@endpushonce

<x-cms::layout.admin :head="$head" :url="$url">
    <div class="main-page">
        <div class="main-page-block logo">
            @include('cms::components.icons.logo')
        </div>
        <div class="main-page-block info">
            <div class="table main-page-table">
                <div class="cell main-page-cell left">
                    <x-cms-navigation />
                </div>
                <div class="cell main-page-cell right">
                    <table>
                        @foreach ($data as $header => $items)
                            <tr class="header">
                                <td colspan="2">
                                    <span class="text-big">{{ $header }}</span>
                                </td>
                            </tr>
                            @foreach ($items as $title => $value)
                                <tr class="data">
                                    <td class="title">
                                        <span class="text">{{ $title }}</span>
                                    </td>
                                    <td class="value">
                                        <span class="text" dir="ltr">{!! $value !!}</span>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-cms::layout.admin>
