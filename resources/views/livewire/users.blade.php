<div class="p-6">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="createShowModal">
            {{ __('Kreiraj novog korisnika') }}
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
                                Naziv
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Role
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
                                        {{$item->email}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{$item->role}}
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
                                <td class="px-6 py-4 text-sm whitespace-no-wrap" colspan="4">Nije pronađen ni jedan
                                    rezultat. Nema korisnika za ubacivanje.
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
            {{ __('Upravljanje korisnicima') }}
        </x-slot>

        <x-slot name="content">
            <div class="col-mt-4">
                <x-jet-label for="name" value="{{ __('Naziv korisnika') }}"/>
                <x-jet-input id="name" class="inline-block mt-1 md:w-2/4" type="text" name="name"
                             wire:model.debounce.800ms="name"/>
                @if($modelId)
                    <x-jet-button class="ml-2 inline-block md:w-1/4" wire:click="updateName"
                                  wire:loading.attr="disabled">
                        {{ __('Ažuriraj naziv') }}
                    </x-jet-button>
                @endif
                @error('name')<span class="error block">{{$message}}</span>@enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="email" value="{{ __('Email adresa') }}"/>
                <x-jet-input id="email" class="inline-block mt-1 md:w-2/4" type="email" name="email"
                             wire:model.debounce.800ms="email"/>
                @if($modelId)
                    <x-jet-button class="ml-2 inline-block md:w-1/4" wire:click="updateEmail"
                                  wire:loading.attr="disabled">
                        {{ __('Ažuriraj email') }}
                    </x-jet-button>
                @endif
                @error('email')<span class="error block">{{$message}}</span>@enderror
            </div>
            <div class="mt-4" x-data="{eyeColor:true}">
                <x-jet-label for="password" value="{{ __('Lozinka') }}"/>
                <x-jet-input id="password" class="inline-block mt-1 md:w-2/4"
                             x-bind:type="eyeColor ? 'password' : 'text'" name="password"
                             wire:model.debounce.800ms="password"/>
                <i @click="eyeColor= !eyeColor" :aria-expanded="eyeColor ? 'true':'false'"
                   :class="{'eyeActive':eyeColor}" class="fas fa-eye eyeDef"></i>
                @if($modelId)
                    <x-jet-button class="ml-2 inline-block md:w-1/4" wire:click="updatePassword"
                                  wire:loading.attr="disabled">
                        {{ __('Ažuriraj password') }}
                    </x-jet-button>
                @endif
                @error('password') <span class="error block">{{ $message }}</span> @enderror
            </div>
            <div class="mt-4">
                <x-jet-label for="role" value="{{ __('Role') }}"/>
                <select wire:model="role" id=""
                        class="md:w-2/4 inline-block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="">-- Odaberite privilegije --</option>
                    @foreach (App\Models\User::userRoleList() as $key => $value)
                        <option value="{{ $key }}" {{$this->role==$value?'selected':''}}>{{ $value }}</option>
                    @endforeach
                </select>
                @if($modelId)
                    <x-jet-button class="ml-2 inline-block md:w-1/4" wire:click="updateRole"
                                  wire:loading.attr="disabled">
                        {{ __('Ažuriraj privilegije') }}
                    </x-jet-button>
                @endif
                @error('role') <span class="error block">{{ $message }}</span> @enderror
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

            @if(!$modelId)
                <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                    {{ __('Kreiraj') }}
                </x-jet-button>

            @endif
        </x-slot>
    </x-jet-dialog-modal>

    {{-- MODAL FOR DELETITION --}}
    <x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">

        <x-slot name="title">
            {{ __('Brisanje korisnika') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Da li ste sigurni da želite da obrišete korisnika ') }}<b>{{ $this->name }}</b>?
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                {{ __('Obriši korisnika') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>



</div>
