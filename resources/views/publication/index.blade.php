<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list :url="route('admin.publication.list', [], false)" id="publication" />
</x-cms::layout.admin>
