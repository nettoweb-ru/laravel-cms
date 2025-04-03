<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list
        :url="route('admin.language.list', [], false)"
        id="language"
        :columns="[
            'id' => __('cms::main.attr_id'),
            'sort' => __('cms::main.attr_sort'),
            'name' => __('cms::main.attr_name'),
            'slug' => __('cms::main.attr_slug'),
            'locale' => __('cms::main.attr_locale'),
            'is_default' => __('cms::main.attr_is_default'),
        ]"
        :default="['sort', 'name', 'slug']"
    />
</x-cms::layout.admin>
