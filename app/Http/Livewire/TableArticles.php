<?php

namespace App\Http\Livewire;

use App\Models\Article;
use App\Models\Cart;
use App\Models\Table;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TableArticles extends Component
{
    public $category;
    public $subCategory;
    public $subCategoryId;
    public $modalShowMoreAboutArticle = false;
    public $modalShowSuccessfulAddToCart = false;
    public $articleId;
    public $chosenArticle;
    public $articleName;
    public $articleCena;
    public $articleAkcija;
    public $articleAkcijskaCena;
    public $articleOpis;
    public $articlePicture;
    public $qrNumFromUrl;
    public $sessid;
    public $opis;


    public function getSubCategoryId()
    {
        $categoryId = DB::table('sub_categories')->select('id')->where('name', '=', $this->subCategory)->get()->pluck('id');
        return $categoryId[0];
    }

    public function createShowModal($id)
    {
        $this->articleId = $id;
        $this->chosenArticle = $this->getSingleArticle($this->articleId);
        $this->articleName = $this->chosenArticle->name;
        $this->articleCena = $this->chosenArticle->cena;
        $this->articleAkcija = $this->chosenArticle->akcija;
        $this->articleAkcijskaCena = $this->chosenArticle->akcijska;
        $this->articlePicture = $this->chosenArticle->picture;
        $this->opis = $this->chosenArticle->opis;
        /*$this->articleOpis= $this->chosenArticle->opis;*/
        $this->modalShowMoreAboutArticle = true;
    }

    public function addToCart($id)
    {
        $this->articleId = $id;
        $this->chosenArticle = $this->getSingleArticle($this->articleId);
        $this->articleName = $this->chosenArticle->name;
        $this->articleCena = $this->chosenArticle->cena;
        $this->articleAkcija = $this->chosenArticle->akcija;
        $this->articleAkcijskaCena = $this->chosenArticle->akcijska;
        $this->articlePicture = $this->chosenArticle->picture;
        $this->opis = $this->chosenArticle->opis;
        $this->addItemToCart();
        $this->modalShowSuccessfulAddToCart = true;
    }

    public function addItemToCart()
    {
        $cart = $this->createAcquiredCartItem();
        Cart::create($cart);

    }

    public function createAcquiredCartItem()
    {
        return[
            'session'=>$this->sessid,
            'article'=>$this->articleName,
            'articleId'=>$this->articleId,
            'cena'=>$this->articleAkcija ? $this->articleAkcijskaCena : $this->articleCena,
            'qrNum'=>$this->qrNumFromUrl,
            'tableId'=>$this->getTableid($this->qrNumFromUrl)
        ];
    }
    public function getTableid($qr){
        $tid = Table::select('id')->where('qr_num','=',$qr)->get()->pluck('id');
        return $tid[0];
    }

    public function getSingleArticle($id)
    {
        return Article::find($id);
    }

    public function readArticles()
    {
        return Article::where('subcategory_id', '=', $this->subCategoryId)->get();
    }

    public function render()
    {
        $this->subCategoryId = $this->getSubCategoryId();
        return view('livewire.table-articles', [
            'data' => $this->readArticles(),
        ]);
    }
}
