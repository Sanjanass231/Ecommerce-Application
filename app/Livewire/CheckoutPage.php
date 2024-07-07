<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Order;
use App\Models\Address;
use App\Mail\OrderPlaced;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Mail;
use App\Helpers\CartManagement;

#[Title('Checkout Page')]
class CheckoutPage extends Component
{
    public $firstName;
    public $lastName;
    public $phone;
    public $streetAddress;
    public $city;
    public $state;
    public $zipCode;
    public $paymentMethod;

    public function mount(){
        $cartItems = CartManagement::getCartItemsFromCookie(); 
        if(count($cartItems) == 0){
            return redirect('/products');
        }
    
    }
public function placeOrder()
{
    $this->validate([
        'firstName' => 'required',
        'lastName' => 'required',
        'phone' => 'required',
        'streetAddress' => 'required',
        'city' => 'required',
        'state' => 'required',
        'zipCode' => 'required',
        'paymentMethod' => 'required',
    ]);

    $cartItems = CartManagement::getCartItemsFromCookie();
    $lineItems = [];
    $validCartItems = [];
    foreach ($cartItems as $item) {
        // Check if the product ID exists in the products table
        if (\App\Models\Product::find($item['product_id'])) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'inr',
                    'unit_amount' => $item['unitAmount'] * 100,
                    'product_data' => [
                        'name' => $item['name'],
                    ]
                ],
                'quantity' => $item['quantity'],
            ];
            $validCartItems[] = $item;
        }
    }

    if (empty($validCartItems)) {
        // Handle the case where there are no valid cart items
        session()->flash('error', 'Invalid cart items. Please try again.');
        return redirect()->back();
    }

    $order = new Order();
    $order->user_id = auth()->user()->id;
    $order->grandTotal = CartManagement::calculateGrandTotal($validCartItems);
    $order->paymentMethod = $this->paymentMethod;
    $order->paymentStatus = 'pending';
    $order->status = 'new';
    $order->currency = 'inr';
    $order->shippingAmount = 0;
    $order->shippingMethod = 'none';
    $order->notes = 'Order placed by ' . auth()->user()->name;
    $order->save();

    $address = new Address();
    $address->firstName = $this->firstName;
    $address->lastName = $this->lastName;
    $address->phone = $this->phone;
    $address->streetAddress = $this->streetAddress;
    $address->city = $this->city;
    $address->state = $this->state;
    $address->zipCode = $this->zipCode;
    $address->order_id = $order->id;
    $address->save();

    $orderItems = [];
    foreach ($validCartItems as $item) {
        $orderItems[] = [
            'product_id' => $item['product_id'],
            'quantity' => $item['quantity'],
            'unitAmount' => $item['unitAmount'],
            'totalAmount' => $item['totalAmount'],
        ];
    }

    $order->items()->createMany($orderItems);

    CartManagement::clearCartItems();

    $redirectUrl = '';
    if ($this->paymentMethod == 'stripe') {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $sessionCheckout = Session::create([
            'payment_method_types' => ['card'],
            'customer_email' => auth()->user()->email,
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);
        $redirectUrl = $sessionCheckout->url;
    } else {
        $redirectUrl = route('success');
    }
    $order->save();
      $address->order_id = $order->id;
      $address->save();
      $order->items()->createMany($cartItems);
      CartManagement::clearCartItems();
      Mail::to(request()->user())->send(new OrderPlaced($order));
      return redirect($redirectUrl);
    
}

    public function render()
    {
        $cartItems = CartManagement::getCartItemsFromCookie();
        $grandTotal = CartManagement::calculateGrandTotal($cartItems);
        return view('livewire.checkout-page',[
            'cartItems'=>$cartItems,
            'grandTotal'=>$grandTotal
        ]);
    }
}
