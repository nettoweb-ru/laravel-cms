<x-cms::layout.admin :title="$title" :chain="$chain">
    <x-cms::list :url="route('admin.language.list', [], false)" id="language" />
</x-cms::layout.admin>
