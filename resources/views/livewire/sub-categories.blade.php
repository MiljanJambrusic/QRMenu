<div class="p-6">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        Da biste videli promenu slike osvežite stranicu:
        <x-jet-button class="mr-3 ml-2" style="background:#b02e80;" onclick="location.reload();">
            {{ __('Osveži') }}
        </x-jet-button>
        <x-jet-button wire:click="createShowModal">
            {{ __('Kreiraj potkategoriju') }}
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
                                Naziv potkategorije
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Pripada kategoriji
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
                                        {{$item->category_name}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        <img class="max-h-48" src="{{\App\Helper\Helper::URL_PATH. "/storage/images/sub_category/".$item->picture}}">
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
                                <td class="px-6 py-4 text-sm whitespace-no-wrap" colspan="4">Trenutno nemate ni jednu
                                    unetu potkategoriju.
                                </td>
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
            {{ __('Upravljanje potkategorijama') }}
        </x-slot>

        <x-slot name="content">
            <div class="col-mt-4">
                <x-jet-label for="name" value="{{ __('Naziv potkategorije') }}"/>
                <x-jet-input id="name" class="inline-block mt-1 md:w-2/4" type="text" name="name"
                             wire:model.debounce.800ms="name"/>
                @if($modelId)
                    <x-jet-button class="ml-2" wire:click="updateOnlyName" wire:loading.attr="disabled">
                        {{ __('Ažuriraj naziv') }}
                    </x-jet-button>
                @endif
                @error('name')<span class="error">{{$message}}</span>@enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="category_id" value="{{ __('Kategorija') }}"/>
                <select wire:model="category_id" id=""
                        class="inline-block appearance-none md:w-2/4 bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option>-- Odaberite kategoriju --</option>
                    @foreach (App\Models\Category::CategoriesList() as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>

                @error('category_id') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="picture" value="{{ __('Slika za potkategoriju') }}"/>
                <input id="picture" class="inline-block mt-1 md:w-2/4" type="file" name="picture"
                       wire:model.debounce.800ms="picture"/>
                @if($modelId)

                    <x-jet-button class="ml-2" wire:click="updateOnlyPicture" wire:loading.attr="disabled">
                        {{ __('Ažuriraj sliku') }}
                    </x-jet-button>
                @endif
                @error('picture')<span class="error">{{$message}}</span>@enderror
                <div wire:loading wire:target="picture" class="block mt-2 font-bold">Ubacivanje slike...</div>
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

                <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                    {{ __('Ažuriraj') }}
                </x-jet-button>
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
            {{ __('Brisanje potkategorije') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Da li ste sigurni da želite da obrišete potkategoriju ').$this->name."?" }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Obriši potkategoriju') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
