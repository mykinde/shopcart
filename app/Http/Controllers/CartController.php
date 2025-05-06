<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{
    public function cart()
    {
        return view('cart');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->back();
    }

    public function deleteProduct(Request $request)
    {
        if ($request->product_code) {
            $cart = session()->get('cart');
            if (isset($cart[$request->product_code])) {
                unset($cart[$request->product_code]);
                session()->put('cart', $cart);
            }
        }
    }
}
