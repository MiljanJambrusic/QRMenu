<div class="p-6">
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button class="mr-3 ml-3" style="background:#b02e80;" onclick="location.reload();">
            {{ __('Osveži') }}
        </x-jet-button>
    </div>
    @if($this->pollingStartStop)
        <div wire:poll.5000ms.visible="refreshDataTimeout">
            Trenutna je: {{now()}}
        </div>
    @endif
    {{-- The data table--}}
    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Broj porudžbine
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Ukupna cena
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Broj osoba
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Poručeno
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Završena porudžbina
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                Kreirana
                            </th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"></th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @if($data->count())
                            @foreach($data as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        <b>{{$item->id}}.</b>
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{number_format($item->total_price,2)}} RSD
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{$item->people_num}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{$item->ordered}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{$item->done}}
                                    </td>
                                    <td class="px-6 py-4 text-sm whitespace-no-wrap">
                                        {{date("H:i:s",strtotime($item->created_at))}}
                                    </td>

                                    <td class="px-6 py-4 text-sm whitespace-no-wrap flex justify-end">
                                        <x-jet-button class="ml-2" wire:click="OpenOrderModal({{$item->id}})">
                                            {{ __('Opširnije') }}
                                        </x-jet-button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="px-6 py-4 text-sm whitespace-no-wrap text-center font-bold text-lg"
                                    colspan="7">Trenutno nemate ni jednu porudžbinu.
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
            {{ __('Opširnije') }}
        </x-slot>

        <x-slot name="content">
            @if(count($items_from_order)>0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Artikal</th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Količina</th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Cena</th>
                    </tr>
                    </thead>
                @foreach($this->items_from_order as $order_item)
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-6 py-4 text-sm whitespace-no-wrap font-bold">{{$loop->iteration}}.</td>
                            <td class="px-6 py-4 text-sm whitespace-no-wrap" style="font-size: 20px;font-weight: 600">{{$order_item->article_name}}</td>
                            <td class="px-6 py-4 text-sm whitespace-no-wrap" style="font-size: 20px;font-weight: 600">{{$order_item->count}}</td>
                            <td class="px-6 py-4 text-sm whitespace-no-wrap" style="font-size: 20px;font-weight: 600">{{$order_item->total_price}}</td>
                        </tr>
                        </tbody>
                @endforeach
                </table>
            @endif

        </x-slot>
        <x-slot name="footer">
            <x-jet-button class="ml-2" wire:click="finishOrder">
                {{ __('Završi porudžbinu') }}
            </x-jet-button>
            <x-jet-secondary-button wire:click="disableModalAndStartPolling" wire:loading.attr="disabled">
                {{ __('Odustani') }}
            </x-jet-secondary-button>

        </x-slot>
    </x-jet-dialog-modal>
</div>
