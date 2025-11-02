<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain">
    <x-cms::list
        id="album"
        :url="route('admin.album.list')"
        :columns="[
            'id' => __('main.attr_id'),
            'sort' => __('main.attr_sort'),
            'name' => __('main.attr_name'),
        ]"
        :default="['sort', 'name']"
        :defaultSort="['sort' => 'asc']"
        :title="__('main.list_album')"
        :actions="[
            'create' => route('admin.album.create'),
            'delete' => route('admin.album.delete'),
            'downloadCsv' => route('admin.album.download-csv'),
        ]"
    />
</x-cms::layout.admin>
