<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Cookie;
use App\Models\Product;

class CartManagement{
    // add item to cart
       static public function addItemToCart($productId){
        $cartItems = self::getCartItemsFromCookie();

        $existingItem = null;

        foreach($cartItem as $key => $item){
            if($item['$productId'] == $productId){
                $existingItem= $key;
                break;
            }
        }
        if($existingItem !== null){
            $cartItems[$existingItem]['quantity']++;
            $cartItems[$existingItem]['totalAmount'] = $cartItems[$existingItem]['quantity'] * $cartItems[$existingItem]['unitAmount'];
       }
       else{
        $product = Product::where('id',$productId)->first(['id','name','price','images']);
         if($product){
            $cartItems[] = [
         'product_id' => $productId,
         'name'=> $product->name,
         'image'=> $product->images[0],
         'quantity'=> 1,
         'unitAmount'=> $product->price,
         'totalAmount'=> $product->price,
            ];
         }
       }
       self::addCartItemsToCookie($cartItems);
       return count($cartItems);
       }

    // remove item to cart
     static public function removeCartItems($productId){
       $cartItems = self::getCartItemsFromCookie();
       
       foreach($cartItems as $key => $item){
         if($item['product_id'] == $productId){
            unset($cartItems[$key]);
         }
       }
       self::addCartItemsToCookie($cartItems);
       return $cartItems;
     }
    // add cart items to cookie
    static public function addCartItemsToCookie($cartItems){
       Cookie::queue('cartItems',json_encode($cartItems),60*24*30);
    }
    // clear cart items from cookie
    static public function clearCartItems(){
        Cookie::queue(Cookie::forget('cartItems'));
     }
    // get all cart items form cookie
    static public function getCartItemsFromCookie($cartItems){
        $cartItems = json_encode(Cookie::get('cartItems'),true);
        if(!$cartItems){
            $cartItems = [];
        }
    }
    // increment item quantity
   static public function incrementQuantityToCartItem($productId){
    $cartItems = self::getCartItemsFromCookie();
    foreach($cartItems as $key => $item){
        if($item['product_id']==$productId){
            $cartItems[$key]['quantity']++;
            $cartItems[$key]['totalAmount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unitAmount'];
        }
    }
    self::addCartItemsToCookie($cartItems);
    return $cartItems;

   }
    // decrement item quantity
    static public function decrementQuantityToCartItem($productId){
        $cartItems = self::getCartItemsFromCookie();
        foreach($cartItems as $key => $item){
            if($item['product_id']==$productId){
                if($cartItems[$key]['quantity'] > 1){
                    $cartItems[$key]['quantity']--;
                    $cartItems[$key]['totalAmount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unitAmount'];
                } 
            }
        }
        self::addCartItemsToCookie($cartItems);
        return $cartItems;
    
       }
    // calculate grand total

    static public function calculateGrandTotal($items){
        return array_sum(array_col($items,'totalAmount'));
    }
}