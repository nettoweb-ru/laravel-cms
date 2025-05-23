<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain">
    <x-cms::list
        id="language"
        :url="route('admin.language.list')"
        :columns="[
            'id' => __('main.attr_id'),
            'sort' => __('main.attr_sort'),
            'name' => __('main.attr_name'),
            'slug' => __('main.attr_slug'),
            'locale' => __('main.attr_locale'),
            'is_default' => __('main.attr_is_default'),
        ]"
        :default="['sort', 'name', 'slug']"
        :defaultSort="['sort' => 'asc']"
        :title="__('main.list_language')"
        :actions="[
            'create' => route('admin.language.create'),
            'delete' => route('admin.language.delete'),
        ]"
    />
</x-cms::layout.admin>
