<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms::tabs id="navigation-tabs-{{ (int) $object->id }}" :tabs="[1 => 'main.general_properties', 2 => 'main.list_menu_item']" :conditions="[2 => $object->exists]">
        <x-slot name="tab1">
            <x-cms-form id="navigation-group" :url="$url" :method="$method" :objectId="$object->id" :apply="!$object->getAttribute('is_system')" :save="!$object->getAttribute('is_system')">
                <x-slot name="sheet1">
                    <x-cms::form.string name="sort" width="1" maxlength="3"
                        :label="__('main.attr_sort')"
                        :value="old('sort', $object->getAttribute('sort'))"
                        :messages="$errors->get('sort')"
                        :disabled="$object->getAttribute('is_system')"
                    />
                    <x-cms::form.string name="name" width="11" maxlength="255"
                        :label="__('main.attr_name')"
                        :value="old('name', $object->getAttribute('name'))"
                        :messages="$errors->get('name')"
                        :disabled="$object->getAttribute('is_system')"
                        :required="true"
                        :autofocus="true"
                    />
                </x-slot>
            </x-cms-form>
        </x-slot>
        @if ($object->exists)
            <x-slot name="tab2">
                <x-cms::list
                    :url="route('admin.navigation-item.list', ['navigation' => $object])"
                    id="navigation-items-{{ $object->id }}"
                    :columns="[
                        'id' => __('main.attr_id'),
                        'sort' => __('main.attr_sort'),
                        'name' => __('main.attr_name'),
                        'url' => __('main.attr_link'),
                    ]"
                    :default="['sort', 'name']"
                    :defaultSort="['sort' => 'asc']"
                    :actions="[
                        'create' => route('admin.navigation-item.create', ['navigation' => $object]),
                        'delete' => route('admin.navigation-item.delete'),
                        'toggle' => route('admin.navigation-item.toggle'),
                    ]"
                    :noSort="['name']"
                />
            </x-slot>
        @endif
    </x-cms::tabs>
</x-cms::layout.admin>
