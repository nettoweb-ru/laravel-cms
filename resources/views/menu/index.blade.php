<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list :url="route('admin.menu.list', [], false)" />
</x-cms::layout.admin>
