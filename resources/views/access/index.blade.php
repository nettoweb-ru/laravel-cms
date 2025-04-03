<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list
        :url="route('admin.role.list', [], false)"
        id="role"
        :columns="[
            'id' => __('cms::main.attr_id'),
            'name' => __('cms::main.attr_name'),
            'slug' => __('cms::main.attr_slug'),
        ]"
        :default="['name']"
    />
    <x-cms::list
        :url="route('admin.permission.list', [], false)"
        id="permission"
        :columns="[
            'id' => __('cms::main.attr_id'),
            'name' => __('cms::main.attr_name'),
            'slug' => __('cms::main.attr_slug'),
        ]"
        :default="['name']"
    />
</x-cms::layout.admin>
