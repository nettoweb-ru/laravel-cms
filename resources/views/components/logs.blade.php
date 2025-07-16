@pushonce('head')
    @vite([
        'resources/css/netto/logs.css',
        'resources/js/netto/logs.js',
    ])
@endpushonce

@props([
    'class' => 'js-logs',
    'id' => 'logs',
    'url',
])

<x-cms::ajaxlist :id="$id" :url="$url" :class="$class" :showTotal="false">
    <div class="logs js-body" data-delete-url="{{ route('admin.log.delete') }}">

    </div>
</x-cms::ajaxlist>
