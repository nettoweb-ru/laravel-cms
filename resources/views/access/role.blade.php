<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms-form id="role" :url="$url" :method="$method" :objectId="$object->id" :apply="!$object->getAttribute('is_system')" :save="!$object->getAttribute('is_system')">
        <x-slot name="sheet1">
            <x-cms::form.string name="name" width="6" maxlength="255"
                :label="__('main.attr_name')"
                :value="old('name', $object->getAttribute('name'))"
                :messages="$errors->get('name')"
                :required="true"
                :autofocus="true"
                :disabled="$object->getAttribute('is_system')"
            />
            <x-cms::form.string name="slug" width="6" maxlength="255"
                :label="__('main.attr_slug')"
                :value="old('slug', $object->getAttribute('slug'))"
                :messages="$errors->get('slug')"
                :required="true"
                :disabled="$object->getAttribute('is_system')"
            />
            @permission('admin-access')
                <x-cms::form.checkbox name="permissions"
                    :label="__('main.list_permission')"
                    :value="old('permissions', $object->permissions->pluck('id')->all())"
                    :options="$reference['permission']"
                    :multiple="true"
                    :disabled="$object->getAttribute('is_system')"
                />
            @endpermission
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
