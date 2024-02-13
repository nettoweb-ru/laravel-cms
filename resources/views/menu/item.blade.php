<x-cms::layout.admin :title="$title" :chain="$chain" :header="$header">
    <x-cms-form :url="$url" :method="$method" :objectId="$object->id">
        <x-slot name="sheet1">
            <x-cms::form.string name="sort" type="text" width="1" maxlength="8" :label="__('cms::main.attr_sort')" :value="old('sort', $object->sort)" :messages="$errors->get('sort')" />
            <x-cms::form.string name="name" type="text" width="5" maxlength="255" :label="__('cms::main.attr_name')" :value="old('name', $object->name)" :messages="$errors->get('name')" required autofocus />
            <x-cms::form.string name="slug" type="text" width="6" maxlength="255" :label="__('cms::main.attr_slug')" :value="old('slug', $object->slug)" :messages="$errors->get('slug')" />
            <x-cms::form.string name="link" type="text" width="6" maxlength="255" :label="__('cms::main.attr_link')" :value="old('link', $object->link)" :messages="$errors->get('link')" />
            <x-cms::form.checkbox name="is_active" width="3" type="radio" :label="__('cms::main.attr_is_active')" :value="old('is_active', $object->is_active)" :messages="$errors->get('is_active')" :options="$reference['boolean']" />
            <x-cms::form.checkbox name="is_blank" width="3" type="radio" :label="__('cms::main.attr_is_blank')" :value="old('is_blank', $object->is_blank)" :messages="$errors->get('is_blank')" :options="$reference['boolean']" />
            @permission('manage-access')
            <x-cms::form.checkbox name="roles" :label="__('cms::main.list_role')" :value="old('roles', $object->roles->pluck('id')->all())" :options="$reference['role']" :messages="$errors->get('roles')" multiple />
            @endpermission
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
