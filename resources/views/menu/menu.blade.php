<x-cms::layout.admin :title="$title" :chain="$chain" :header="$header">
    <x-cms::tabs id="menu_tab" :tabs="[1 => 'cms::main.general_properties', 2 => 'cms::main.list_menu_item']" :conditions="[2 => !empty($object->id)]">
        <x-slot name="tab1">
            <x-cms-form :url="$url" :method="$method" :objectId="$object->id">
                <x-slot name="sheet1">
                    <x-cms::form.string name="name" type="text" width="6" maxlength="255" :label="__('cms::main.attr_name')" :value="old('name', $object->name)" :messages="$errors->get('name')" required autofocus />
                    <x-cms::form.string name="slug" type="text" width="3" maxlength="255" :label="__('cms::main.attr_slug')" :value="old('slug', $object->slug)" :messages="$errors->get('slug')" required />
                    <x-cms::form.select name="lang_id" width="3" :options="$reference['language']" :label="__('cms::main.attr_language')" :value="old('lang_id', $object->lang_id)" :messages="$errors->get('lang_id')" required />
                    <x-cms::form.select name="menu_item_id" :options="$reference['menu_items']" :label="__('cms::main.attr_menu_item_id')" :value="old('menu_item_id', $object->menu_item_id)" :messages="$errors->get('menu_item_id')" />
                </x-slot>
            </x-cms-form>
        </x-slot>
        @if ($object->id)
            <x-slot name="tab2">
                <x-cms::list :url="route('admin.menu.menuItem.list', ['menu' => $object], false)" id="menu-item-{{ $object->id }}" />
            </x-slot>
        @endif
    </x-cms::tabs>
</x-cms::layout.admin>
