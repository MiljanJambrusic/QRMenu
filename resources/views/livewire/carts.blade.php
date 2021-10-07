<div class="regular-body" wire:poll.10000ms="refreshDataTimeout">
    <a class="icon-back" href="javascript:history.back()"><i class="fas fa-reply"></i></a>
    <h3 class="text-center" style="color:#fff;">Korpa {{$this->tableId}}</h3>
    <div class="mt-4">
        <span class="span-info-2">Ažurirano: <span> {{$this->now}}</span></span>
        <span class="span-info-2">Ukupna cena: <span> {{number_format($this->current_total_price,2)}} rsd</span></span>
        <span class="span-info-2">Broj osoba: <span> {{$this->cntOfPeople}}</span></span>
    </div>
    <hr class="hrcust">
    @if(count($items)>0)
        @foreach($items as $item)
            <span class="span-info-2 mt-3 mb-3">Osoba : <span>{{$loop->iteration}}</span></span>
            @foreach($item as $itemArticle)
                <span class="span-info-2">{{$loop->iteration}} - <span>{{$itemArticle->article}} | {{number_format($itemArticle->cena,2)}} rsd</span>
                @if($itemArticle->session==$this->sessid)
                        --
                        <x-jet-danger-button class="ml-2 del-btn"
                                             wire:click="deleteShowModal({{$itemArticle->id}},'{{$itemArticle->article}}')">
                        <i class="fas fa-trash"></i>
                    </x-jet-danger-button>
                    @endif
                    </span>
            @endforeach
            <hr class="hrcust">
        @endforeach
        <div class="w-full text-center">
            <x-jet-button class="mx-auto" style="font-size:14px;" wire:click="makeOrder">Poruči</x-jet-button>
            <span class="span-info-2 mt-1 text-center" style="margin-left:0;"><span> {{number_format($this->current_total_price,2)}} rsd</span></span>
        </div>
    @else
        <span class="span-info-2 mt-1 text-center" style="margin-left:0;"><span>Vaša korpa je prazna.</span></span>
    @endif
    <br>
    @if(isset($orders))
        <h4 class="text-center mt-2" style="color:#f5ea62;">Vaše porudžbine</h4>
        @foreach($orders as $orderid)
            <div class="mt-2">
                <span class="span-info-2" style="color:#f5ea62;font-size:20px;">Porudžbina br: {{$loop->iteration}}</span>
                @if($orderid['done']==0)
                    <span class="span-info-2" style="color:#ed1a1e;font-size:20px;">Priprema: U pripremi</span>
                @else
                    <span class="span-info-2" style="color:#f5ea62;font-size:20px;">Priprema: Završeno</span>
                @endif
                <hr class="hrcust">
                @foreach($allOrders as $order)
                        <span class="span-info-2">Naziv: <span> {{$order['article_name']}}</span></span>
                        <span class="span-info-2">Cena: <span> {{number_format($order['total_price']/$order['count'],2)}} rsd</span></span>
                        <span class="span-info-2">Ukupna cena: <span> {{number_format($order['total_price'],2)}} rsd</span></span>
                        <span class="span-info-2">Količina: <span> {{$order['count']}}</span></span>
                        <hr class="hrcust">
                @endforeach
            </div>
        @endforeach
    @endif

    {{-- MODAL FOR DELETITION --}}
    <x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">
        <x-slot name="title">
            {{ __('Brisanje iz narudžbine') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Da li ste sigurni da želite da obrišete ') }}
            <span><b>{{$this->name_to_delete."?"}}</b></span>
        </x-slot>

        <x-slot name="footer">
            <div class="w-full row">
                <div class="col-6">
                    <x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')"
                                            wire:loading.attr="disabled">
                        {{ __('Odustani') }}
                    </x-jet-secondary-button>
                </div>
                <div class="col-6">
                    <x-jet-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
                        {{ __('Obriši') }}
                    </x-jet-danger-button>
                </div>
            </div>
        </x-slot>
    </x-jet-dialog-modal>
</div>
