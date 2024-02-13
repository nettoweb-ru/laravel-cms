<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list :url="route('admin.role.list', [], false)" />
    <x-cms::list :url="route('admin.permission.list', [], false)" />
</x-cms::layout.admin>
