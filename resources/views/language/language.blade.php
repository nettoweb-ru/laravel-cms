<x-cms::layout.admin :title="$title" :chain="$chain" :header="$header">
    <x-cms-form :url="$url" :method="$method" :objectId="$object->id">
        <x-slot name="sheet1">
            <x-cms::form.string name="sort" type="text" width="1" maxlength="8" :label="__('cms::main.attr_sort')" :value="old('sort', $object->sort)" :messages="$errors->get('sort')" />
            <x-cms::form.string name="name" type="text" width="4" maxlength="255" :label="__('cms::main.attr_name')" :value="old('name', $object->name)" :messages="$errors->get('name')" required autofocus />
            <x-cms::form.string name="slug" type="text" width="2" maxlength="2" :label="__('cms::main.attr_slug')" :value="old('slug', $object->slug)" :messages="$errors->get('slug')" required />
            <x-cms::form.string name="locale" type="text" width="2" maxlength="5" :label="__('cms::main.attr_locale')" :value="old('locale', $object->locale)" :messages="$errors->get('locale')" required />
            <x-cms::form.checkbox name="is_default" width="3" type="radio" :label="__('cms::main.attr_is_default')" :value="old('is_default', $object->is_default)" :messages="$errors->get('is_default')" :options="$reference['boolean']" />
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
