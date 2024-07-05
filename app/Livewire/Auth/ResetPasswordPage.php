<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attribute\Url;
use Livewire\Attribute\Title;
use App\Models\user;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

#[Title('Reset Password')]
class ResetPasswordPage extends Component
{   
    #[Url] 
    public $email;
    public $token;
    public $password;
    public $password_confirmation;

    public function mount($mount){
        $this->token = $token;
    }

    public function save(){
        $this->validate([
            'token'=>'required',
            'email'=>'required|email',
             'password'=>'required|min:8|max:20|confirmed',
        ]);

        $status = Password::reset([
  'email'=>$this->email,
  'password'=>$this->password,
  'password_confirmation'=>$this->password_confirmation,
  'token'=>$this->token
        ],
        function(User $user, string $password){
            $password = $this->password;
            $user->forceFill([
                'password'=>Hash::make($password)
            ])->setRememberToken(Str::random(60));
            $user->save();
            event(new PasswordReset($user));
        }
    );
    return $status === Password::PASSWORD_RESET?redirect('/login'):session()->flash('error','Something went wrong');

    }

    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
