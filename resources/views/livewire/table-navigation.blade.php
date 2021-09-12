<div>
    <nav class="nav flex navbg fixed-top">
        <a class="nav-link flex-1" aria-current="page" href="{{\App\Helper\Helper::URL_PATH."/table?qrNum=".@$_GET['qrNum']}}"><i class="ico fas fa-home mr-2 mt-2"></i></a>
        <a class="nav-link flex-1 text-center menu-bg" href="{{\App\Helper\Helper::URL_PATH."/table?qrNum=".@$_GET['qrNum']}}" aria-disabled="true">
            <img class="mx-auto" width="50" height="50" src="{{\App\Helper\Helper::URL_LOGO}}"></a>
        <a class="nav-link flex-1 text-right" aria-current="page" href="{{\App\Helper\Helper::URL_PATH."/cart?qrNum=".@$_GET['qrNum']}}"><i class="ico fas fa-shopping-cart mr-2 mt-2"></i></a>
    </nav>
</div>
