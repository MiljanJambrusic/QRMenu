<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class TableSubCategories extends Component
{
    public $category;
    public $categoryId;
    public $listOfSubCategories;
    public $qrNumFromUrl;

    public function getCategoryId(){

        $categoryId = DB::table('categories')->select('id')->where('name','=',$this->category)->get()->pluck('id');
        return $categoryId[0];
    }

    public function getAllSubCategories($categoryId){
        return DB::table('sub_categories')->select('id','name','picture','category_id')->where('category_id','=',$categoryId)->get()->toArray();
    }
    public function onStart(){
        //Get the ID of category from Table
        $this->categoryId = $this->getCategoryId();
        $this->listOfSubCategories = $this->getAllSubCategories($this->categoryId);
    }


    public function render()
    {
        $this->onStart();
        return view('livewire.table-sub-categories');
    }
}
