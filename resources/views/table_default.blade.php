<?php
session_start();

$segments = \Illuminate\Support\Facades\Request::segments();
$num_of_segments = count($segments);

$qrNum = @$_GET['qrNum'];

if (!isset($qrNum))
    abort(403, 'Stranica nije pronađena ili QR kod nije aktivan.');
else{
    $checkQRNum = \Illuminate\Support\Facades\DB::table('tables')->where('qr_num','=',$qrNum)->get();
    if(count($checkQRNum)==0){
        abort(403,'Stranica nije pronađena ili QR kod nije validan');
    }
}
?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @trixassets
@livewireStyles

<!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="antialiased body-bg">
@livewire('table-navigation',['sessid'=>session_id()])
@if($num_of_segments==1)
    @if($segments[0]=='cart')
        @livewire('carts',['qrNumFromUrl'=>$qrNum,'sessid'=>session_id()])
    @else
        <div class="welcome-text regular-body">
            <span>Dobrodošli</span>
            <span>Na</span>
            <span>QR Meni</span>
            <a class="btn btn-default btn-prim animate-pulse" href="{{\App\Helper\Helper::URL_PATH."/table/categories?qrNum=".@$_GET['qrNum']}}">Pogledajte meni</a>
            <div class="block text-center mt-4">
                    <img width="150" height="150" class="mx-auto" src="{{\App\Helper\Helper::URL_LOGO}}">
            </div>
        </div>
    @endif

@elseif($num_of_segments==2)
    @livewire('table-categories',['qrNumFromUrl'=>$qrNum,'sessid'=>session_id()])
@elseif($num_of_segments==3)
    @livewire('table-sub-categories',['category'=>$segments[2],'qrNumFromUrl'=>$qrNum,'sessid'=>session_id()])
@elseif($num_of_segments==4)
    @livewire('table-articles',['category'=>$segments[2],'subCategory'=>$segments[3],'qrNumFromUrl'=>$qrNum,'sessid'=>session_id()])

@endif


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
@stack('modals')

@livewireScripts
</body>
</html>
