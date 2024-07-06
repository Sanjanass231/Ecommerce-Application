<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Helpers\CartManagement;

#[Title('Checkout Page')]
class CheckoutPage extends Component
{
    public function render()
    {
        $cartItems = CartManagement::getCartItemsFromCookie();
        $grandTotal = CartManagement::calculateGrandTotal($cartItems);
        return view('livewire.checkout-page',[
            'cartItems'=>$cartItems,
            'grandTotal'=>$grandTotal
        ]);
    }
}
