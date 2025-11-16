<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain">
    <x-cms::list
        id="redirect"
        :url="route('admin.redirect.list')"
        :columns="[
            'id' => __('main.attr_id'),
            'source' => __('main.attr_source'),
            'destination' => __('main.attr_destination'),
            'is_regexp' => __('main.attr_is_regexp'),
            'status' => __('main.attr_status'),
        ]"
        :default="['source', 'destination']"
        :defaultSort="['source' => 'asc']"
        :title="__('main.list_redirect')"
        :actions="[
            'create' => route('admin.redirect.create'),
            'delete' => route('admin.redirect.delete'),
            'toggle' => route('admin.redirect.toggle'),
            'downloadCsv' => route('admin.redirect.download-csv'),
        ]"
        :ltr="['source', 'destination']"
    />
</x-cms::layout.admin>
