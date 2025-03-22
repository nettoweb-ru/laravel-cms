<x-cms::layout.admin :title="$title" :chain="$chain" :header="$header">
    <x-cms-form :url="$url" :method="$method" :objectId="$object->id" :lang="true">
        <x-slot name="sheet1">
            <x-cms::form.string name="sort" type="text" width="1" maxlength="8" :label="__('cms::main.attr_sort')" :value="old('sort', $object->sort)" :messages="$errors->get('sort')" />
            <x-cms::form.string name="caption" type="text" width="11" maxlength="255" :label="__('cms::main.attr_caption')"
                                :value="$object->getMultiLangOldValue('caption')"
                                :messages="get_errors_multilang($errors, 'caption')" multilang autofocus />
            <x-cms::form.file name="filename" :label="__('cms::main.attr_filename')" :value="$object->filename" :messages="$object->getUploadErrors($errors, 'filename')" required />
        </x-slot>
    </x-cms-form>
</x-cms::layout.admin>
