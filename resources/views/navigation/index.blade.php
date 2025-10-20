<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain">
    <x-cms::list
        id="navigation"
        :url="route('admin.navigation.list')"
        :columns="[
            'id' => __('main.attr_id'),
            'sort' => __('main.attr_sort'),
            'name' => __('main.attr_name'),
        ]"
        :default="['sort', 'name']"
        :defaultSort="['sort' => 'asc']"
        :title="__('main.general_navigation')"
        :actions="[
            'create' => route('admin.navigation.create'),
            'delete' => route('admin.navigation.delete'),
        ]"
        :noSort="['name']"
        :search="false"
    />
</x-cms::layout.admin>
