<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list :url="route('admin.user.list', [], false)" />
</x-cms::layout.admin>
