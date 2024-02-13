<x-cms::layout.admin :title="$title" :chain="$chain" :header="$header">
    <x-cms-form :url="$url" :method="$method" :objectId="$object->id">
        <x-slot name="sheet1">
            <x-cms::form.string name="name" type="text" width="6" maxlength="255" :label="__('cms::main.attr_name')" :value="old('name', $object->name)" :messages="$errors->get('name')" required autofocus />
            <x-cms::form.string name="slug" type="text" width="6" maxlength="255" :label="__('cms::main.attr_slug')" :value="old('slug', $object->slug)" :messages="$errors->get('slug')" required />
            <x-cms::form.checkbox name="permissions" :label="__('cms::main.list_permission')" :value="old('permissions', $object->permissions->pluck('id')->all())" :options="$reference['permission']" :messages="$errors->get('permissions')" multiple />
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
