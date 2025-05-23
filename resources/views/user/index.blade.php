<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain">
    <x-cms::list
        id="user"
        :url="route('admin.user.list')"
        :columns="[
            'id' => __('main.attr_id'),
            'name' => __('auth.name'),
            'email' => __('auth.email'),
            'email_verified_at' => __('main.attr_email_verified_at'),
            'created_at' => __('main.attr_created_at'),
            'updated_at' => __('main.attr_updated_at'),
        ]"
        :default="['name', 'email']"
        :defaultSort="['name' => 'asc']"
        :title="__('main.list_user')"
        :actions="[
            'create' => route('admin.user.create'),
            'delete' => route('admin.user.delete'),
        ]"
    />
</x-cms::layout.admin>
