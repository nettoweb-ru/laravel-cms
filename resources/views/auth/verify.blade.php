<x-cms::layout.guest :head="$head">
    <x-cms-form :url="['save' => route('admin.verification.send')]" method="post" :apply="false" :save="false">
        <x-slot name="sheet1"></x-slot>
        <x-slot name="buttons">
            <x-cms::form.button class="btn-form btn-normal">{{ $btnTitle }}</x-cms::form.button>
        </x-slot>
    </x-cms-form>
</x-cms::layout.guest>
