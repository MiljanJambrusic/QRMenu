<?php

namespace App\Http\Livewire;

use App\Models\Cart;
use App\Models\FoodOrder;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use function Symfony\Component\String\b;

class Carts extends Component
{
    public $sessid;
    public $qrNumFromUrl;
    public $article;
    public $articleId;
    public $cena;
    public $tableId;
    public $tableName;
    public $qrNum;
    public $createdAt;
    public $updatedAt;
    public $cntOfPeople = 0;
    public $now;
    public $items_from_cart;
    public $current_total_price = 0;
    public $id_to_delete;
    public $name_to_delete;
    public $num_of_orders = 0;
    public $allOrders = [];
    public $modal_already_ordered = false;
    public $modalConfirmDeleteVisible = false;
    public $orderExists = false;
    public $onlyOrders;


    public function getItems()
    {
        $items = Cart::where('qrNum', '=', $this->qrNumFromUrl)->orderBy('session')->get();
        return $items;
    }

    //Fuction that checks out data
    public function refreshDataTimeout()
    {
        //This polling triggers render again and again
    }

    public function makeOrder()
    {
        /*$checkIfOrderExists = Order::select('id')->where('table_id', '=', $this->tableId)->get();
        if (count($checkIfOrderExists) > 0) {
            $this->modal_already_ordered = true;
        } else {
            return 'bbbb';*/
        //Make order
        //Treba mi total price
        //People number
        //Treba mi table id
        $objOrder = $this->createModelForOrder();
        Order::create($objOrder);
        $order_id = Order::select('id')->where('table_id', '=', $this->tableId)->get()[0]['id'];
        $items_to_insert = $this->createModelFoodOrder($this->items_from_cart, $order_id, $this->tableId, $this->tableName);
        FoodOrder::insert($items_to_insert);
        $this->removeItemsFromCart($this->items_from_cart);
        $this->render();
    }


    public function getTableId()
    {
        $results = Table::select('id', 'name')->where('qr_num', '=', $this->qrNumFromUrl)->get();
        if (isset($results)) {
            $this->tableId = $results[0]['id'];
            $this->tableName = $results[0]['name'];
        }
    }

    public function sortItems($items)
    {
        //Group by sessions
        $this->current_total_price = 0;
        $itemsArray = array();
        $cnt = 0;
        $tmpSession = $items[0]->session;
        $itemsArray[$cnt] = array();
        $cnt++;
        foreach ($items as $item) {
            if ($tmpSession != $item->session) {
                $itemsArray[$cnt] = array();
                $tmpSession = $item->session;
                $cnt++;
            }
            $this->current_total_price += $item->cena;
        }
        $tmpSession = $items[0]->session;
        $this->cntOfPeople = $cnt;
        $cnt = 0;
        foreach ($items as $item) {
            if ($this->sessid === $item->session) {
                array_push($itemsArray[0], $item);
            } else {
                if ($tmpSession != $item->session) {
                    $tmpSession = $item->session;
                    $cnt++;
                }
                array_push($itemsArray[$cnt], $item);
            }
        }
        return $itemsArray;
    }

    public function addToCart($item)
    {
        Cart::create($item);
    }

    public function removeItemsFromCart($items)
    {
        foreach ($items as $item) {
            Cart::where('id', $item['id'])->delete();
        }
    }

    /**
     *  Shows the delete confirmation modal.
     */
    public function deleteShowModal($id, $name)
    {
        $this->id_to_delete = $id;
        $this->name_to_delete = $name;
        $this->modalConfirmDeleteVisible = true;
    }

    public function delete()
    {
        Cart::destroy($this->id_to_delete);
        $this->modalConfirmDeleteVisible = false;
        $this->render();
    }

    public function createModelForOrder()
    {
        return [
            'total_price' => $this->current_total_price,
            'people_num' => $this->cntOfPeople,
            'done' => 0,
            'ordered' => 1,
            'table_id' => $this->tableId
        ];
    }

    public function createModelFoodOrder($items, $oid, $tid, $tname)
    {
        $items_array_to_insert = array();
        foreach ($items as $item) {
            array_push($items_array_to_insert, [
                'order_id' => $oid,
                'article_id' => $item['articleId'],
                'article_name' => $item['article'],
                'price' => $item['cena'],
                'quantity' => 1,
                'session' => $item['session'],
                'table_name' => $tname,
                'table_id' => $tid,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        return $items_array_to_insert;
    }

    public function getOrderByTableId($table_id)
    {
        $past2hours = trim(date("Y-m-d H:i:s", strtotime('-2 hours')));
        $data = Order::select('id','done')->where('table_id', '=', $table_id)
            ->where('updated_at', '>=', $past2hours)->get()->pluck('id','done');
        return $data;
    }

    public function getAllOrdersByIds($order_ids)
    {
        $data = FoodOrder::select('order_id', 'article_name', DB::raw("(sum(quantity)) as count"), DB::raw("(sum(price)) as total_price"))->whereIn('order_id', $order_ids)->groupBy('article_name', 'order_id')->orderBy('order_id', 'asc')->get();
        return $data;
    }

    public function getDoneStateOrders($order_id)
    {
        $data = Order::select('id','done')->whereIn('id', $order_id)
            ->get();
        return $data;
    }

    public function render()
    {
        $this->now = date('d.m.Y. H:i:s');
        $this->getTableId();
        $items = $this->getItems();
        $this->items_from_cart = $items;
        if (count($items) > 0) {
            $items = $this->sortItems($items);
        }
        //Check if some orders already exists
        $checkOrders = $this->getOrderByTableId($this->tableId);
        if (count($checkOrders) > 0) {
            $this->orderExists = true;
            //Get all orders ( possible multiple orders for the same table )
            $this->allOrders = $this->getAllOrdersByIds($checkOrders);
            $this->onlyOrders = $this->getDoneStateOrders($checkOrders);
        } else {
            $this->orderExists = false;
        }

        return view('livewire.carts', [
            'items' => $items,
            'allOrders' => $this->allOrders,
            'orders' => $this->onlyOrders
        ]);
    }
}
