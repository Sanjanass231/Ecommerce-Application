<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Brand;
use App\Models\OrderItem;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id','brand_id','name','slug','images','description','price','isActive','isFeatured','inStock','onSale'
    ];

    protected $casts = [
     'images' => 'array'
    ];
    
   
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }


}
