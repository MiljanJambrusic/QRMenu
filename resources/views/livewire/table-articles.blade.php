<div class="regular-body">
    <a class="icon-back" href="javascript:history.back()"><i class="fas fa-reply"></i></a>
    <span class="span-info">Kategorija: <span> {{$category}}</span></span>
    <span class="span-info">Grupa: <span> {{$subCategory}}</span></span>

    <div class="w-full flex justify-center flex-col mt-3">
        @foreach($data as $artikal)
            <div class="w-full flex mt-3">
                <div class="article-image-wrapper">
                    <img src="{{\App\Helper\Helper::URL_PATH. "/storage/images/articles/".$artikal->picture}}"
                         class="w-full">
                </div>
                <div class="article-info ml-3">
                    <span><label>Naziv:</label> {{$artikal->name}}</span><br>
                    @if($artikal->akcija)
                        <span class="active-price">Akcijska ponuda:<br> {{number_format($artikal->akcijska)}} RSD</span>
                        <br>
                        <del>Stara cena: {{number_format($artikal->cena,2)}} RSD</del>
                    @else
                        <span class="active-price">Cena: {{number_format($artikal->cena,2)}} RSD</span><br>
                    @endif
                    <div class="mt-3 text-center">
                        <x-jet-button class="btn btn-default btn-prim" wire:click="createShowModal({{$artikal->id}})">
                            {{ __('Sastojci') }}
                        </x-jet-button>
                        <x-jet-button style="margin-top:10px;" wire:click="addToCart({{$artikal->id}})">
                            {{ __('Dodaj u korpu') }}
                        </x-jet-button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <x-jet-dialog-modal wire:model="modalShowMoreAboutArticle">
        <x-slot name="title">
            <span style="font-size: 18px;font-weight: 600;color:purple;">{{ __('Sastojci') }}</span>
        </x-slot>

        <x-slot name="content">
            @if($opis!="")
                Opis:<br><span style="font-size:18px;font-weight: 500">{{$opis}}</span>
            @else
                <br><span style="font-size:18px;font-weight: 500">Za ovaj artikal nisu uneti dodatne informacije o sastojcima</span>
            @endif

        </x-slot>
        <x-slot name="footer">
            <x-jet-danger-button class="ml-2" wire:click="$toggle('modalShowMoreAboutArticle')">
                {{ __('Zatvori') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>

    <x-jet-dialog-modal wire:model="modalShowSuccessfulAddToCart">
        <x-slot name="title">
            Uspešno ste dodali <b>{{$articleName}}</b> u korpu
        </x-slot>
        <x-slot name="content">
        </x-slot>
        <x-slot name="footer">
            <x-jet-danger-button class="ml-2" wire:click="$toggle('modalShowSuccessfulAddToCart')">
                {{ __('Zatvori') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-dialog-modal>
</div>



