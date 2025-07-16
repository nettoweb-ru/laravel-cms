<x-cms::layout.admin :head="$head" :url="$url" :header="$header">
    <x-cms::logs
        id="logs"
        :url="route('admin.log.list')"
        :actions="[
            'delete' => route('admin.log.delete'),
        ]"
    />
</x-cms::layout.admin>
