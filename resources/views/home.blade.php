<x-cms::layout.admin :head="$head" :url="$url">
    <div class="main-page">
        <div class="main-page-block logo">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 323.41 62.74" xml:space="preserve">
                @include('cms::components.icons.logo')
            </svg>
        </div>
        <div class="main-page-table table">
            <div class="main-page-cell cell left">
                <x-cms-navigation/>
            </div>
            <div class="main-page-cell cell right">
                <div class="main-page-block params">
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
