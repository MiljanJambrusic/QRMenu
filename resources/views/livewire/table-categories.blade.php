<div class="regular-body">
    {{-- Because she competes with no one, no one can compete with her. --}}
    <div class="w-full flex justify-center">
        <h1 class="h1-naslov">Kategorije</h1>
    </div>
    <div class="w-full flex justify-center flex-col">
        @foreach($data as $category)
            <div class="common-item">
                <a href="{{"categories/".$category->name }}?qrNum={{$qrNumFromUrl}}" class="p-2 flex flex-col">
                    <div class="image-wrapper">
                        <img src="{{\App\Helper\Helper::URL_PATH. "/storage/images/category/".$category->picture}}" class="w-full">
                    </div>
                    <div class="common-item-text-header text-center">
                        <span>{{$category->name}}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>
