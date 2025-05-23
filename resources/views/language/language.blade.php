<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms-form id="language" :url="$url" :method="$method" :objectId="$object->id">
        <x-slot name="sheet1">
            <x-cms::form.string name="sort" width="1" maxlength="3"
                :label="__('main.attr_sort')"
                :value="old('sort', $object->getAttribute('sort'))"
                :messages="$errors->get('sort')"
            />
            <x-cms::form.string name="name" width="4" maxlength="255"
                :label="__('main.attr_name')"
                :value="old('name', $object->getAttribute('name'))"
                :messages="$errors->get('name')"
                :required="true"
                :autofocus="true"
            />
            <x-cms::form.string name="slug" width="2" maxlength="2"
                :label="__('main.attr_slug')"
                :value="old('slug', $object->getAttribute('slug'))"
                :messages="$errors->get('slug')"
                :required="true"
            />
            <x-cms::form.string name="locale" width="2" maxlength="5"
                :label="__('main.attr_locale')"
                :value="old('locale', $object->getAttribute('locale'))"
                :messages="$errors->get('locale')"
                :required="true"
            />
            <x-cms::form.checkbox name="is_default" width="3"
                :label="__('main.attr_is_default')"
                :value="old('is_default', $object->getAttribute('is_default'))"
                :messages="$errors->get('is_default')"
                :options="$reference['boolean']"
            />
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
