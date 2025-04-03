<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list
        :url="route('admin.publication.list', [], false)"
        id="publication"
        :columns="[
            'id' => __('cms::main.attr_id'),
            'name' => __('cms::main.attr_name'),
            'slug' => __('cms::main.attr_slug'),
            'lang_id' => __('cms::main.attr_language'),
            'created_at' => __('cms::main.attr_created_at'),
            'updated_at' => __('cms::main.attr_updated_at'),
        ]"
        :default="['name']"
    />
</x-cms::layout.admin>
