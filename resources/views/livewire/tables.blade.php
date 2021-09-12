<div class="p-6">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="createShowModal">
            {{ __('Kreiraj novi sto') }}
        </x-jet-button>
    </div>


    {{-- The data table--}}

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">

                    <table class="min-w-full divide-y divide-gray-200 aaaa">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Naziv
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Broj stolica
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                QR code
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
                                        {{$item->num_seats}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{$item->qr_num}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap flex justify-end">
                                        <x-jet-button class="ml-2 bg-green-400 hover:bg-green-500 border-none"
                                                      wire:click="previewQR({{$item->id}})">
                                            {{ __('Preview QR') }}
                                        </x-jet-button>
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
                                <td class="px-6 py-4 text-sm whitespace-no-wrap" colspan="4">No Results Found</td>
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
            {{ __('Upravljanje stolovima') }}
        </x-slot>

        <x-slot name="content">
            <div class="col-mt-4">
                <x-jet-label for="name" value="{{ __('Naziv stola') }}"/>
                <x-jet-input id="name" class="block mt-1 md:w-2/4" type="text" name="name"
                             wire:model.debounce.800ms="name"/>
                @error('name')<span class="error">{{$message}}</span>@enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="num_seats" value="{{ __('Broj stolica') }}"/>
                <x-jet-input id="num_seats" class="block mt-1 md:w-20" min="1" type="number" name="num_seats"
                             wire:model.debounce.800ms="num_seats"/>
                @error('num_seats')<span class="error">{{$message}}</span>@enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="qr_num" value="{{ __('QR kod') }}"/>
                <div class="flex">
                    <x-jet-input disabled id="qr_num" class="block mt-1 md:w-2/4" min="1" type="text" name="qr_num"
                                 wire:model.debounce.800ms="qr_num"/>
                    <x-jet-button class="ml-2" wire:click="generateQR">
                        {{ __('Generiši QR') }}
                    </x-jet-button>
                </div>
                @error('qr_num')<span class="error">{{$message}}</span>@enderror
            </div>
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
            {{ __('Brisanje stola') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Da li ste sigurni da želite da obrišete ovaj sto?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Obriši sto') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="modalPreviewQR">

        <x-slot name="title">
            {{ __('Pregled QR koda') }}
        </x-slot>

        <x-slot name="content">
            <div class="div-for-print">
                <h2>Skenirajte QR kod</h2>
                <img id="image-for-print" src="{{asset("/storage/images/qr/".$this->name)}}.png">
                <h2>Da biste videli meni</h2>
            </div>
        </x-slot>

        <x-slot name="footer">
            <button id="btn-for-print" style="border: 2px solid #f5ea62;
    background: #3f423d;
    color: #f5ea62;
    font-weight: 500;
    margin-top: 20px;
    padding: 10px;">Štampaj sliku
            </button>
            <x-jet-danger-button wire:click="$toggle('modalPreviewQR')" wire:loading.attr="disabled">
                {{ __('Zatvori') }}
            </x-jet-danger-button>

        </x-slot>
    </x-jet-dialog-modal>


</div>



