<?php

namespace App\Http\Livewire;


use App\Models\FoodOrder;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Orders extends Component
{

    public $nesto = 0;
    public $recent_data = [];
    public $modalFormVisible = false;
    public $picked_order;
    public $items_from_order = [];
    public $order_id;
    public $pollingStartStop = true;

    public function OpenOrderModal($id){
        $this->order_id = $id;
        $this->pollingStartStop = false;
        $this->modalFormVisible = true;
        $this->getPickedOrder($this->order_id);
    }

    public function finishOrder(){
        $model = [
            'done'=>1
        ];
        Order::find($this->order_id)->update($model);
        $this->pollingStartStop = true;
        $this->modalFormVisible = false;
        $this->render();
    }

    public function getPickedOrder($id){
        $this->items_from_order =  FoodOrder::select('article_name',DB::raw("(sum(quantity)) as count"),DB::raw("(sum(price)) as total_price"))->where('order_id',$id)->groupBy('article_name','session')->orderBy('session','asc')->get();
    }

    public function disableModalAndStartPolling(){
        $this->pollingStartStop = true;
        $this->modalFormVisible = false;
    }

    //Fuction that checks out data
    public function refreshDataTimeout(){
        //This polling triggers render again and againž
    }
    public function getAllData(){
        $this->recent_data = Order::all();
    }
    /**
     * The read function.
     */
    public function read(){
        return Order::where('done','0')->paginate(20);
    }

    public function render()
    {
        $this->getAllData();
        return view('livewire.orders',[
            'data'=>$this->read(),
        ]);
    }
}
