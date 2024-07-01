<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;

#[Title('Product-DetailPage')]
class ProductDetailPage extends Component
{
    public $slug;
    public function mount($slug){
        $this->slug = $slug;
    }
    public function render()
    {
        
        return view('livewire.product-detail-page',[
            'product' => Product::where('slug',$this->slug)->firstOrFail(),
        ]);
    }
}
