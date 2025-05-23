<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms-form id="menu-item" :url="$url" :method="$method" :objectId="$object->id">
        <x-slot name="sheet1">
            <x-cms::form.string name="sort" width="1" maxlength="5"
                :label="__('main.attr_sort')"
                :value="old('sort', $object->getAttribute('sort'))"
                :messages="$errors->get('sort')"
            />
            <x-cms::form.string name="name" width="5" maxlength="255"
                :label="__('main.attr_name')"
                :value="old('name', $object->getAttribute('name'))"
                :messages="$errors->get('name')"
                :required="true"
                :autofocus="true"
            />
            <x-cms::form.string name="slug" width="6" maxlength="255"
                :label="__('main.attr_slug')"
                :value="old('slug', $object->getAttribute('slug'))"
                :messages="$errors->get('slug')"
            />
            <x-cms::form.string name="link" width="6" maxlength="255"
                :label="__('main.attr_link')"
                :value="old('link', $object->getAttribute('link'))"
                :messages="$errors->get('link')"
            />
            <x-cms::form.checkbox name="is_active" width="3"
                :label="__('main.attr_is_active')"
                :value="old('is_active', $object->getAttribute('is_active'))"
                :messages="$errors->get('is_active')"
                :options="$reference['boolean']"
            />
            <x-cms::form.checkbox name="is_blank" width="3"
                :label="__('main.attr_is_blank')"
                :value="old('is_blank', $object->getAttribute('is_blank'))"
                :messages="$errors->get('is_blank')"
                :options="$reference['boolean']"
            />
            <x-cms::form.json name="highlight"
                :label="__('main.attr_highlight')"
                :value="old('highlight', $object->highlight)"
                :messages="$errors->get('highlight')"
            />
            @permission('admin-access')
                <x-cms::form.checkbox name="permissions"
                    :label="__('main.list_permission')"
                    :value="old('permissions', $object->permissions->pluck('id')->all())"
                    :options="$reference['permission']"
                    :multiple="true"
                />
            @endpermission
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
