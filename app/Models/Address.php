<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Order; 

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
       'order_id','firstName','lastName','phone','streetAddress','city','state','zipCode'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function getFullNameAttribute(){
        return "{$this->firstName} {$this->lastName}";
    }
}
