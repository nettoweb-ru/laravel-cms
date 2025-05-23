<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain">
    <x-cms::list
        id="publication"
        :url="route('admin.publication.list')"
        :columns="[
            'id' => __('main.attr_id'),
            'name' => __('main.attr_name'),
            'slug' => __('main.attr_slug'),
            'language.name' => __('main.attr_language'),
            'created_at' => __('main.attr_created_at'),
            'updated_at' => __('main.attr_updated_at'),
        ]"
        :default="['name']"
        :defaultSort="['name' => 'asc']"
        :title="__('main.list_publication')"
        :actions="[
            'create' => route('admin.publication.create'),
            'delete' => route('admin.publication.delete'),
        ]"
    />
</x-cms::layout.admin>
