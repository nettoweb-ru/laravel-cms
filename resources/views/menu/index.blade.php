<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list
        :url="route('admin.menu.list', [], false)"
        id="menu"
        :columns="[
            'id' => __('cms::main.attr_id'),
            'name' => __('cms::main.attr_name'),
            'slug' => __('cms::main.attr_slug'),
            'lang_id' => __('cms::main.attr_language'),
        ]"
        :default="['name']"
    />
</x-cms::layout.admin>
