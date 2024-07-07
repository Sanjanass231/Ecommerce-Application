<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('My Orders')]
class MyOrdersPage extends Component
{
    use WithPagination;

    public function render()
    {
        $myOrders = Order::where('user_id',auth()->id())->latest()->paginate(2);

        return view('livewire.my-orders-page',[
            'orders'=>$myOrders
        ]);
    }
}
