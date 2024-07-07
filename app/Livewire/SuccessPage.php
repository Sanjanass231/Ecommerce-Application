<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Order;

#[Title('Success')]
class SuccessPage extends Component
{    
    #[Url]
    public $sessionId;

    public function render()
    {
        if($this->sessionId){
            Stripe::setApiLey(env('STRIPE_SECRET'));
            $sessionInfo = Session::retrieve($this->sessionId);

            if($sessionInfo->paymentStatus != 'paid'){
                $latestOrder->paymentStatus = 'failed';
                $latestOrder->save();
                return redirect()->route('cancel');
            }
            else if($sessionInfo->paymentStatus == 'paid'){
                $latestOrder->paymentStatus = 'paid';
                $latestOrder->save();
                return redirect()->route('success');
            }
        }
        $latestOrder = Order::with('address')->where('user_id',auth()->user()->id)->latest()->first();
        return view('livewire.success-page',[
            'order' => $latestOrder
        ]);
    }
}
