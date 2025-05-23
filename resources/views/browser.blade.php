<x-cms::layout.admin :head="$head" :url="$url" :header="$header">
    <x-cms::browser
        id="browser-public"
        :url="route('admin.browser.list')"
        :root="get_storage_path($disk)"
        :dir="DIRECTORY_SEPARATOR"
        :actions="[
            'delete' => route('admin.browser.delete'),
            'upload' => route('admin.browser.upload'),
            'directory' => route('admin.browser.directory'),
        ]"
    />
</x-cms::layout.admin>
