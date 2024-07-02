<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Livewire\Attributes\Url;

#[Title('Products-Page')]
class ProductsPage extends Component
{
    #[Url]
    public $selected_categories = [];

    #[Url]
    public $selected_brands = [];

    #[Url]
    public $featured = [];

    #[Url]
    public $onSale = [];

    #[Url]
    public $priceRange = 300000;

    #[Url]
    public $sort = 'latest';

    public function render()
    {
        $productQuery = Product::query()->where('isActive',1);

        if(!empty($this->selected_categories)){
               $productQuery->whereIn('category_id',$this->selected_categories);
        }
        if(!empty($this->selected_brands)){
               $productQuery->whereIn('category_id',$this->selected_brands);
        }
        if($this->featured){
               $productQuery->where('isFeatured',1);
        }
        if($this->onSale){
               $productQuery->where('onSale',1);
        }
        if($this->priceRange){
               $productQuery->whereBetween('price',[0,$this->priceRange]);
        }
        if($this->sort=='latest'){
               $productQuery->latest();
        }
        if($this->sort=='price'){
               $productQuery->orderBy('price');
        }

        return view('livewire.products-page',[
            'products'=> $productQuery->paginate(6),
            'brands'=>Brand::where('isActive',1)->get(['id','name','slug']),
            'categories'=>Category::where('isActive',1)->get(['id','name','slug']),
            ''
        ]);
    }
}
