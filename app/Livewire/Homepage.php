<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Brand;
use App\Models\Category;

#[Title('Home Page')]
class Homepage extends Component
{
    public function render()
    {
        $brands = Brand::where('isActive',1)->get(); 
        $categories = Category::where('isActive',1)->get();  
 
        return view('livewire.homepage',[
            'brands'=>$brands,
            'categories'=>$categories
        ]);
    }
}
