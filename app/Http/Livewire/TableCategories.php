<?php

namespace App\Http\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TableCategories extends Component
{
    public $name;
    public $picture;
    public $qrNumFromUrl;

    /**
     * The read function.
     */
    public function read(){
        return DB::table('categories')->get();
    }

    public function render()
    {
        return view('livewire.table-categories',[
            'data'=>$this->read(),
        ]);
    }
}
