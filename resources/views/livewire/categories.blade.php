<?php
?>
<div class="p-6">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        Da biste videli promenu slike osvežite stranicu:
        <x-jet-button class="mr-3" style="background:#b02e80;" onclick="location.reload();">
            {{ __('Osveži') }}
        </x-jet-button>
        <x-jet-button wire:click="createShowModal">
            {{ __('Kreiraj kategoriju') }}
        </x-jet-button>
    </div>


    {{-- The data table--}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Naziv kategorije
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Slika
                            </th>

                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @if($data->count())
                            @foreach($data as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{$item->name}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        <img class="max-h-48" src="{{\App\Helper\Helper::URL_PATH. "/storage/images/category/".$item->picture}}">
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap flex justify-end">
                                        <x-jet-button class="ml-2" wire:click="updateShowModal({{$item->id}})">
                                            {{ __('Ažuriraj') }}
                                        </x-jet-button>
                                        <x-jet-danger-button class="ml-2" wire:click="deleteShowModal({{$item->id}})">
                                            {{ __('Obriši') }}
                                        </x-jet-danger-button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-6 py-4 text-sm whitespace-no-wrap" colspan="4">Trenutno nemate ni jednu unetu kategoriju.</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <br/>
    {{$data->links()}}

    {{-- SECTION FOR MODALS --}}

    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Upravljanje kategorijama') }}
        </x-slot>

        <x-slot name="content">
            <div class="col-mt-4">
                <x-jet-label for="name" value="{{ __('Naziv kateogije') }}"/>
                <x-jet-input id="name" class="block mt-1 md:w-2/4" type="text" name="name"
                             wire:model.debounce.800ms="name"/>
                @error('name')<span class="error">{{$message}}</span>@enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="picture" value="{{ __('Slika kategorije') }}"/>
                <input id="picture" class="block mt-1 md:w-4/5" type="file" name="picture"
                             wire:model.debounce.800ms="picture"/>
                @error('picture')<span class="error">{{$message}}</span>@enderror
                <div wire:loading wire:target="picture">Ubacivanje slike...</div>
            </div>
            @if ($picture)
                <div class="mt-4">
                    Pregled učitane fotografije za kategoriju:
                        <img src="{{ $picture->temporaryUrl() }}">
                </div>
            @endif
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

            @if($modelId)

                <x-jet-danger-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                    {{ __('Ažuriraj') }}
                </x-jet-danger-button>
            @else
                <x-jet-danger-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                    {{ __('Kreiraj') }}
                </x-jet-danger-button>

            @endif
        </x-slot>
    </x-jet-dialog-modal>

    {{-- MODAL FOR DELETITION --}}
    <x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">

        <x-slot name="title">
            {{ __('Brisanje kategorije') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Da li ste sigurni da želite da obrišete kategoriju ').$this->name."?" }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Obriši kategoriju') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>



