<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use Livewire\Component;

class TableNavigation extends Component
{
    protected $listeners = ['resetCounter' => 'resetCounter'];
    public $sessid;
    public $korpaCounter = 0;
    public $korpa;



    public function resetCounter()
    {
        $this->korpa = Cart::select('id')->where('session','=',$this->sessid)->get();
        $this->korpaCounter = count($this->korpa);
    }

    public function setKorpa(){
        $this->korpa = Cart::where('session','=',$this->sessid)->get();
        $this->korpaCounter = count($this->korpa);
    }

    function render()
    {
        $this->setKorpa();
        return view('livewire.table-navigation');
    }
}
