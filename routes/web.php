<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Homepage;
use App\Livewire\CatgoriesPage;
use App\Livewire\ProductsPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\SuccessPage;
use App\Livewire\CancelPage;

Route::get('/',Homepage::class);
Route::get('/categories',CatgoriesPage::class);
Route::get('/products',ProductsPage::class);
Route::get('/cart',CartPage::class);
Route::get('/products/{product}',ProductDetailPage::class);
Route::get('/checkout',CheckoutPage::class);
Route::get('/my-orders',MyOrdersPage::class);
Route::get('/my-orders/{order}',MyOrderDetailPage::class);
Route::get('/login',LoginPage::class);
Route::get('/register',RegisterPage::class);
Route::get('/forgot',ForgotPasswordPage::class);
Route::get('/reset',ResetPasswordPage::class);
Route::get('/success',SuccessPage::class);
Route::get('/cancel',CancelPage::class);
