<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain">
    <x-cms::list
        id="menu"
        :url="route('admin.menu.list')"
        :columns="[
            'id' => __('main.attr_id'),
            'name' => __('main.attr_name'),
            'slug' => __('main.attr_slug'),
            'language.name' => __('main.attr_language'),
        ]"
        :default="['name']"
        :defaultSort="['name' => 'asc']"
        :title="__('main.list_menu')"
        :actions="[
            'create' => route('admin.menu.create'),
            'delete' => route('admin.menu.delete'),
            'downloadCsv' => route('admin.menu.download-csv'),
        ]"
    />
</x-cms::layout.admin>
