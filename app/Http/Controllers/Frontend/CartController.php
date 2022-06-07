<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetails;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $details = $this->getOrCreateCart()->details();

        $data = [
            "cart" => $cart,
            "details" => $details
        ];
        return response($data);
    }

    /**
     *
     * Lists the cart content
     *
     * @return Cart
     */
    private function getOrCreateCart(): Cart
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->user_id, 'is_active' => true],
            ['code' => Str::random(8)]
        )->with('details')->get()->first();
        return $cart;
    }

    /**
     * Add product as cart detail
     *
     * @param Product $product
     * @param int $quantity
     */
    public function add(Product $product, int $quantity = 1)
    {
        $cart = $this->getOrCreateCart();
        $cart->details()->create([
            "product_id" => $product->product_id,
            "quantity" => $quantity,
        ]);

        $details = $cart->details();

        $data = [
            "cart" => $cart,
            "details" => $details
        ];
        return response($data);
    }

    /**
     *
     * Remove cart detail from cart
     *
     * @param CartDetails $cartDetails
     */
    public function remove(CartDetails $cartDetails)
    {
        $cart = $this->getOrCreateCart();
        $details = $this->getOrCreateCart()->details();

        $data = [
            "cart" => $cart,
            "details" => $details
        ];
        return response($data);
    }
}
