<table class="logs-table">
    @foreach ($items as $key => $item)
        <tr class="log-header">
            <td colspan="2">
                <div class="table table-log-head">
                    <div class="cell cell-log-head left">
                        <span class="text-big" dir="ltr">{{ $key }}</span>
                    </div>
                    <div class="cell cell-log-head right">
                        <button class="btn btn-bg btn-blue download js-list-button" data-type="download" data-filename="{{ get_relative_path(storage_path('logs/'.$key)) }}" title="{{ __('main.title_download') }}"></button>
                        <button class="btn btn-bg btn-red remove js-list-button" data-type="delete" data-filename="{{ $key }}" title="{{ __('main.title_upload_file_delete') }}"></button>
                    </div>
                </div>
            </td>
        </tr>
        @foreach ($item['items'] as $entry)
            <tr class="log-entry">
                <td class="log-date">
                    <span class="text">{{ format_date($entry['logged_at']) }}</span><br />
                    <span class="text-small {{ $entry['level'] }}">{{ $entry['env'] }}.{{ $entry['level'] }}</span>
                </td>
                <td class="log-message">
                    <div class="log-message-hold">
                        <span class="text" dir="ltr">{!! soft_break_string($entry['message']) !!}</span>
                    </div>
                </td>
            </tr>
        @endforeach
    @endforeach
</table>
