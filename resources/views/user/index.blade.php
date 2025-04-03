<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list
        :url="route('admin.user.list', [], false)"
        id="user"
        :columns="[
            'id' => __('cms::main.attr_id'),
            'name' => __('cms::auth.name'),
            'email' => __('cms::auth.email'),
            'email_verified_at' => __('cms::main.attr_email_verified_at'),
            'created_at' => __('cms::main.attr_created_at'),
            'updated_at' => __('cms::main.attr_updated_at'),
        ]"
        :default="['name', 'email']"
    />
</x-cms::layout.admin>
