<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms::tabs id="album-tabs-{{ (int) $object->id }}" :tabs="[1 => 'main.general_properties', 2 => 'main.list_image']" :conditions="[2 => $object->exists]">
        <x-slot name="tab1">
            <x-cms-form id="album" :url="$url" :method="$method" :objectId="$object->id">
                <x-slot name="sheet1">
                    <x-cms::form.string name="sort" width="1" maxlength="5"
                        :label="__('main.attr_sort')"
                        :value="old('sort', $object->getAttribute('sort'))"
                        :messages="$errors->get('sort')"
                    />
                    <x-cms::form.string name="name" width="11" maxlength="255"
                        :label="__('main.attr_name')"
                        :value="old('name', $object->getAttribute('name'))"
                        :messages="$errors->get('name')"
                        :required="true"
                        :autofocus="true"
                    />
                </x-slot>
            </x-cms-form>
        </x-slot>
        @if ($object->exists)
            <x-slot name="tab2">
                <x-cms::gallery
                    id="gallery-{{ $object->id }}"
                    :url="route('admin.album-image.list', ['album' => $object])"
                    :actions="[
                        'create' => route('admin.album-image.create', ['album' => $object]),
                        'delete' => route('admin.album-image.delete', ['album' => $object]),
                    ]"
                />
            </x-slot>
        @endif
    </x-cms::tabs>
</x-cms::layout.admin>
