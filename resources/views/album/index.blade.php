<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list :url="route('admin.album.list', [], false)" />
</x-cms::layout.admin>
