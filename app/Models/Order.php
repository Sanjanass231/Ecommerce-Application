<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Address;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','grandTotal','paymentMethod','paymentStatus','status','currency','shippingAmount','shippingMethod','notes'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function address(){
        return $this->hasOne(Address::class);
    }
    
}
