<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms::tabs id="menu-tabs-{{ (int) $object->id }}" :tabs="[1 => 'main.general_properties', 2 => 'main.list_menu_item']" :conditions="[2 => $object->exists]">
        <x-slot name="tab1">
            <x-cms-form id="menu" :url="$url" :method="$method" :objectId="$object->id">
                <x-slot name="sheet1">
                    <x-cms::form.string name="name" width="6" maxlength="255"
                        :label="__('main.attr_name')"
                        :value="old('name', $object->getAttribute('name'))"
                        :messages="$errors->get('name')"
                        :required="true"
                        :autofocus="true"
                    />
                    <x-cms::form.string name="slug" width="3" maxlength="255"
                        :label="__('main.attr_slug')"
                        :value="old('slug', $object->getAttribute('slug'))"
                        :messages="$errors->get('slug')"
                        :required="true"
                    />
                    <x-cms::form.select name="lang_id" width="3"
                        :label="__('main.attr_language')"
                        :value="old('lang_id', $object->getAttribute('lang_id'))"
                        :messages="$errors->get('lang_id')"
                        :options="$reference['language']"
                        :required="true"
                    />
                    <x-cms::form.select name="menu_item_id"
                        :label="__('main.attr_menu_item_id')"
                        :value="old('menu_item_id', $object->getAttribute('menu_item_id'))"
                        :messages="$errors->get('menu_item_id')"
                        :options="$reference['menu_items']"
                    />
                </x-slot>
            </x-cms-form>
        </x-slot>
        @if ($object->exists)
            <x-slot name="tab2">
                <x-cms::list
                    :url="route('admin.menu-item.list', ['menu' => $object])"
                    id="menu-items-{{ $object->id }}"
                    :columns="[
                        'id' => __('main.attr_id'),
                        'sort' => __('main.attr_sort'),
                        'name' => __('main.attr_name'),
                        'slug' => __('main.attr_slug'),
                        'link' => __('main.attr_link'),
                        'is_blank' => __('main.attr_is_blank'),
                    ]"
                    :default="['sort', 'name']"
                    :defaultSort="['sort' => 'asc']"
                    :actions="[
                        'create' => route('admin.menu-item.create', ['menu' => $object]),
                        'delete' => route('admin.menu-item.delete'),
                        'toggle' => route('admin.menu-item.toggle'),
                    ]"
                />
            </x-slot>
        @endif
    </x-cms::tabs>
</x-cms::layout.admin>
