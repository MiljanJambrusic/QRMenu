<div class="p-6">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <input class="border-solid border border-gray-300 p-2 md:w-1/4 mr-4"
               type="text" placeholder="Pretraga artikala po nazivu, kategorije ili potkategoriji" wire:model="term"/>
        <!--Da biste videli promenu slike osvežite stranicu:
        <x-jet-button class="mr-3 ml-3" style="background:#b02e80;" onclick="location.reload();">
            {{ __('Osveži') }}
        </x-jet-button>-->
        <x-jet-button wire:click="createShowModal">
            {{ __('Novi artikal') }}
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
                                Artikal
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Kategorija
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Potkategorija
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Akcijska cena
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Osnovna cena
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Na akciji
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Slika
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Opis
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
                                        {{$item->subcategory_name}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{$item->akcijska}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{$item->cena}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{$item->akcija}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        <img class="max-h-48" src="{{\App\Helper\Helper::URL_PATH. "/storage/images/articles/".$item->picture}}">
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{$item->opis}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap flex justify-end">
                                        <x-jet-button class="ml-2" wire:click="updateShowModal({{$item->id}})">
                                            {{ __('Ažuriraj') }}
                                        </x-jet-button>
                                        <x-jet-danger-button class="ml-2"
                                                             wire:click="deleteShowModal({{$item->id}})">
                                            {{ __('Obriši') }}
                                        </x-jet-danger-button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-6 py-4 text-sm whitespace-no-wrap text-center font-bold text-lg"
                                    colspan="7">Trenutno nemate ni jedan unet artikal.
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


    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Kreiranje/ažuriranje artikla') }}
        </x-slot>

        <x-slot name="content">
            <div class="col-mt-4">
                <x-jet-label for="name" value="{{ __('Naziv artikla') }}"/>
                <x-jet-input id="name" class="block mt-1 md:w-2/4" type="text" name="name"
                             wire:model.debounce.800ms="name"/>
                @error('name')<span class="error">{{$message}}</span>@enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="category_id" value="{{ __('Kategorija') }}"/>
                <select wire:model="category_id" wire:click="$emit('populateSubCategory')"
                        class="inline-block appearance-none md:w-2/4 bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option>-- Odaberite kategoriju --</option>
                    @foreach (App\Models\Category::CategoriesList() as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="subcategory_id" value="{{ __('Potkategorija') }}"/>
                <select wire:model="subcategory_id"
                        class="inline-block appearance-none md:w-2/4 bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option>-- Odaberite potkategoriju --</option>
                    @foreach ($subcategory_list as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
                @error('subcategory_id') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="col mt-4">
                <x-jet-label for="cena" value="{{ __('Cena bez akcije') }}"/>
                <x-jet-input id="cena" class="block mt-1 md:w-2/4" type="number" min="1" name="cena"
                             wire:model.debounce.800ms="cena"/>
                @error('cena')<span class="error">{{$message}}</span>@enderror
            </div>
            <div class="col mt-4">
                <x-jet-label for="akcijska" value="{{ __('Akcijska cena') }}"/>
                <x-jet-input id="akcijska" class="block mt-1 md:w-2/4" type="number" min="1" name="akcijska"
                             wire:model.debounce.800ms="akcijska"/>
                @error('akcijska')<span class="error">{{$message}}</span>@enderror
            </div>
            <div class="col mt-4">
                <x-jet-label for="opis" value="{{ __('Opis') }}"/>
                <textarea id="opis" style="height: 200px" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block mt-1 md:w-2/4" type="text" min="1" name="opis"
                          wire:model.debounce.800ms="opis"></textarea>

            </div>
            <div class="col mt-4">
                <x-jet-label for="akcija" class="inline-block" value="{{ __('Na akciji') }}"/>
                <x-jet-checkbox id="akcija" class="inline-block mt-1" name="akcija"
                                wire:model.debounce.800ms="akcija"/>
                @error('akcija')<span class="error">{{$message}}</span>@enderror
            </div>
            @if(!$modelId)
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
            {{ __('Brisanje artikla') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Da li ste sigurni da želite da obrišete artikal') }}
            <span><b>{{$this->name."?"}}</b></span>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Obriši artikal') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>
