<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list :url="route('admin.role.list', [], false)" id="role" />
    <x-cms::list :url="route('admin.permission.list', [], false)" id="permission" />
</x-cms::layout.admin>
