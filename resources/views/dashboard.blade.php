<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="text-center font-welcome">
                    Dobrodošli na
                </div>
                <img class='mx-auto animate-pulse' width="200" height="200" src="{{\App\Helper\Helper::URL_LOGO}}">
                <div class="text-center font-welcome">
                    admin panel
                </div>
                <div class="mt-3 mb-5 border-gray-400 border mx-auto" style="width:70%;"></div>
                <div style="width:50%;" class="mx-auto mb-5">
                    <h2 class="text-center mb-3 text-gray-500 text-xl">Moguće opcije</h2>
                    <ul class="possible-options ml-5">
                        @if(Auth::user()->name == 'admin' OR Auth::user()->name == 'sadmin')
                            <li><i class="fas fa-long-arrow-alt-right ml-1 mr-2"></i><b>Stolovi</b> - Upravljanje
                                stolovima, brojem osoba i QR kodovima
                            </li>
                            <li><i class="fas fa-long-arrow-alt-right ml-1 mr-2"></i><b>Korisnici</b> - Upravljanje
                                korisnicima i njihovim privilegijama
                            </li>
                            <li><i class="fas fa-long-arrow-alt-right ml-1 mr-2"></i><b>Kategorije </b> - Upravljanje
                                kategorijama za artikle
                            </li>
                            <li><i class="fas fa-long-arrow-alt-right ml-1 mr-2"></i><b>Podkategorije</b> - Upravljanje
                                podkategorijama, odabir kategorija
                            </li>
                            <li><i class="fas fa-long-arrow-alt-right ml-1 mr-2"></i><b>Artikli</b> - Podešavanje
                                naziva, cena, akcija i dodavanje slike za artikle
                            </li>
                        @endif
                            <li><i class="fas fa-long-arrow-alt-right ml-1 mr-2"></i><b>Porudžbine - </b>Pregled
                                trenutnih porudžbina
                            </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
