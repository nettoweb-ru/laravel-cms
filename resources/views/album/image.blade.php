<x-cms::layout.admin :head="$head" :url="$url" :chain="$chain" :header="$header">
    <x-cms-form id="image" :url="$url" :method="$method" :objectId="$object->id" :lang="true">
        <x-slot name="sheet1">
            <x-cms::form.string name="sort" width="1" maxlength="5"
                :label="__('main.attr_sort')"
                :value="old('sort', $object->getAttribute('sort'))"
                :messages="$errors->get('sort')"
            />
            <x-cms::form.string name="caption" width="11" maxlength="255"
                :label="__('main.attr_caption')"
                :value="old_multilingual('caption', $object)"
                :messages="errors_multilingual('caption', $errors)"
                :multilang="true"
                :autofocus="true"
            />
            <x-cms::form.file name="filename"
                :label="__('main.attr_filename')"
                :value="$object->getAttribute('filename')"
                :messages="errors_upload('filename', $errors)"
                :required="true"
            />
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
