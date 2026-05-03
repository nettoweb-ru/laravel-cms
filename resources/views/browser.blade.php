<x-cms::layout.admin :head="$head" :url="$url" :header="$header">
    <x-cms::browser
        id="{{ config('filesystems.default') }}"
        :url="route('admin.browser.list')"
        :disk="config('filesystems.default')"
        :dir="DIRECTORY_SEPARATOR"
        :actions="[
            'delete' => route('admin.browser.delete'),
            'upload' => route('admin.browser.upload'),
            'directory' => route('admin.browser.directory'),
        ]"
    />
</x-cms::layout.admin>
