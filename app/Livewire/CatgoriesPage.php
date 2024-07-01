<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;

#[Title('Category-Page')]
class CatgoriesPage extends Component
{
    public function render()
    {
        $categories = Category::where('isActive',1)->get();  
        return view('livewire.catgories-page',[
            'categories'=>$categories
        ]);
    }
}
