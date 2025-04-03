<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list
        :url="route('admin.album.list', [], false)"
        id="album"
        :columns="[
            'id' => __('cms::main.attr_id'),
            'sort' => __('cms::main.attr_sort'),
            'name' => __('cms::main.attr_name'),
        ]"
        :default="['sort', 'name']"
    />
</x-cms::layout.admin>
