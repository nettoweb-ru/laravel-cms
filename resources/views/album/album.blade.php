<x-cms::layout.admin :title="$title" :chain="$chain" :header="$header">
    <x-cms::tabs id="album_tab" :current="$tabs['album_tab']" :tabs="[1 => 'cms::main.general_properties', 2 => 'cms::main.list_image']" :conditions="[2 => !empty($object->id)]">
        <x-slot name="tab1">
            <x-cms-form :url="$url" :method="$method" :objectId="$object->id">
                <x-slot name="sheet1">
                    <x-cms::form.string name="sort" type="text" width="1" maxlength="8" :label="__('cms::main.attr_sort')" :value="old('sort', $object->sort)" :messages="$errors->get('sort')" />
                    <x-cms::form.string name="name" type="text" width="11" maxlength="255" :label="__('cms::main.attr_name')" :value="old('name', $object->name)" :messages="$errors->get('name')" required autofocus />
                </x-slot>
            </x-cms-form>
        </x-slot>
        @if ($object->id)
            <x-slot name="tab2">
                <x-cms::gallery :url="route('admin.album.image.list', ['album' => $object], false)" />
            </x-slot>
        @endif
    </x-cms::tabs>
</x-cms::layout.admin>
