<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms-form id="navigation-item" :url="$url" :method="$method" :objectId="$object->id">
        <x-slot name="sheet1">
            <x-cms::form.string name="sort" width="1" maxlength="3"
                :label="__('main.attr_sort')"
                :value="old('sort', $object->getAttribute('sort'))"
                :messages="$errors->get('sort')"
                :disabled="$object->getAttribute('is_system')"
            />
            <x-cms::form.string name="name" width="5" maxlength="255"
                :label="__('main.attr_name')"
                :value="old('name', $object->getAttribute('name'))"
                :messages="$errors->get('name')"
                :disabled="$object->getAttribute('is_system')"
                :required="true"
                :autofocus="true"
            />
            <x-cms::form.string name="url" width="3" maxlength="255"
                :label="__('main.attr_link')"
                :value="old('url', $object->getAttribute('url'))"
                :messages="$errors->get('url')"
                :disabled="$object->getAttribute('is_system')"
                :required="true"
            />
            <x-cms::form.checkbox name="is_active" width="3"
                :label="__('main.attr_is_active')"
                :value="old('is_active', $object->getAttribute('is_active'))"
                :messages="$errors->get('is_active')"
                :options="$reference['boolean']"
            />
            <x-cms::form.json name="highlight"
                :label="__('main.attr_highlight')"
                :value="old('highlight', $object->highlight)"
                :messages="$errors->get('highlight')"
                :disabled="$object->getAttribute('is_system')"
            />
            @permission('admin-access')
                <x-cms::form.checkbox name="permissions"
                    :label="__('main.list_permission')"
                    :value="old('permissions', $object->permissions->pluck('id')->all())"
                    :disabled="$object->getAttribute('is_system')"
                    :options="$reference['permission']"
                    :multiple="true"
                />
            @endpermission
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
