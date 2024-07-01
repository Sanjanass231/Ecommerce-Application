<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

#[Title('Products-Page')]
class ProductsPage extends Component
{
    public function render()
    {
        return view('livewire.products-page',[
            'products'=> Product::query()->where('isActive',1)->paginate(6),
            'brands'=>Brand::where('isActive',1)->get(['id','name','slug']),
            'categories'=>Category::where('isActive',1)->get(['id','name','slug']),
            ''
        ]);
    }
}
