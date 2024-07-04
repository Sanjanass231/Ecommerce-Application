<?php

namespace App\Livewire;
use App\Helpers\CartManagement;
use Livewire\Component;
use App\Livewire\Partials\Navbar;

#[Title('Cart-Page')]
class CartPage extends Component
{
    public $cartItems=[];
    public $grandTotal;

    public function mount(){
        $this->cartItems = CartManagement::getCartItemsFromCookie();
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);
       
    }

    public function increaseQty($productId){
        $this->cartItems = CartManagement::incrementQuantityToCartItem($productId);
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);  
    
    }
    public function decreaseQty($productId){
        $this->cartItems = CartManagement::decrementQuantityToCartItem($productId);
        $this->grandTotal = CartManagement::calculateGrandTotal($this->cartItems);
    }

     
    public function removeItem($productId){
        $this->cartItems=CartManagement::removeCartItems($productId);
        $this->grandTotal=CartManagement::calculateGrandTotal($this->cartItems);
        $this->dispatch('update-cart-count',totalCount:count($this->cartItems))->to(Navbar::class);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
