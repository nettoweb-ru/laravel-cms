<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain">
    <x-cms::list
        id="role"
        :url="route('admin.role.list')"
        :columns="[
            'id' => __('main.attr_id'),
            'name' => __('main.attr_name'),
            'slug' => __('main.attr_slug'),
        ]"
        :default="['name']"
        :defaultSort="['name' => 'asc']"
        :title="__('main.list_role')"
        :actions="[
            'create' => route('admin.role.create'),
            'delete' => route('admin.role.delete'),
        ]"
        :noSort="['name']"
        :search="false"
    />
    <x-cms::list
        id="permission"
        :url="route('admin.permission.list')"
        :columns="[
            'id' => __('main.attr_id'),
            'name' => __('main.attr_name'),
            'slug' => __('main.attr_slug'),
        ]"
        :default="['name']"
        :defaultSort="['slug' => 'asc']"
        :title="__('main.list_permission')"
        :actions="[
            'create' => route('admin.permission.create'),
            'delete' => route('admin.permission.delete'),
        ]"
        :noSort="['name']"
        :search="false"
    />
</x-cms::layout.admin>
