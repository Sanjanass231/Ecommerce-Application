<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Partials\Navbar;
use App\Helpers\CartManagement;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Product;

#[Title('Product-DetailPage')]
class ProductDetailPage extends Component
{
    use LivewireAlert;

    public $quantity = 1;
    public $slug;
    public function mount($slug){
        $this->slug = $slug;
    }

    public function increaseQty(){
        $this->quantity++;
    }
    public function decreaseQty(){
        if($this->quantity > 1)
        $this->quantity--;
    }

    
 public function addToCart($productId){
    $totalCount = CartManagement::addItemToCartWithQty($productId,$this->quantity);
    $this->dispatch('update-cart-count',totalCount:$totalCount)->to(Navbar::class);

    $this->alert('success', 'product added to the cart successfully!', [
           'position' => 'bottom-end',
           'timer' => 3000,
           'toast' => true,
          ]);
}
    public function render()
    {
        
        return view('livewire.product-detail-page',[
            'product' => Product::where('slug',$this->slug)->firstOrFail(),
        ]);
    }
}
