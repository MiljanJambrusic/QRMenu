<div class="regular-body">
    <a class="icon-back" href="javascript:history.back()"><i class="fas fa-reply"></i></a>
    {{-- The Master doesn't talk, he acts. --}}
    <div class="w-full flex justify-center">
        <h1 class="h1-naslov">{{$category}}</h1>
    </div>
    <div class="w-full flex justify-center flex-col">
        @foreach($listOfSubCategories as $subCat)
            <div class="common-item">
                <a href="{{$category."/".$subCat->name }}?qrNum={{$qrNumFromUrl}}" class="p-2 flex flex-col">
                    <div class="image-wrapper">
                        <img src="{{\App\Helper\Helper::URL_PATH. "/storage/images/sub_category/".$subCat->picture}}" class="w-full">
                    </div>
                    <div class="common-item-text-header text-center">
                        <span>{{$subCat->name}}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

</div>
