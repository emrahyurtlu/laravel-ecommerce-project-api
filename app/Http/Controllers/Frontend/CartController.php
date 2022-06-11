<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetails;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        $details = $this->getCartDetails();

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
        $token = PersonalAccessToken::findToken(request()->bearerToken());
        $user = $token->tokenable()->first();
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->user_id, 'is_active' => true],
            ['code' => Str::random(8)]
        );
        return $cart;
    }

    private function getCartDetails() {
        $cart = $this->getOrCreateCart();
        $details = CartDetails::all()->where("cart_id", $cart->cart_id);
        return $details;
    }

    /**
     * Add product as cart detail
     *
     * @param Product $product
     * @param int $quantity
     */
    public function add(Request $request)
    {
        $product_id = $request->get("product");
        $quantity = $request->get("quantity",1);

        $cart = $this->getOrCreateCart();

        $cart->details()->create([
            "product_id" => $product_id,
            "quantity" => $quantity,
        ]);

        $details = $this->getCartDetails();

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
    public function remove(Request $request)
    {
        $cartDetailId = $request->get("cart_detail_id");
        CartDetails::find($cartDetailId)->delete();

        $cart = $this->getOrCreateCart();
        $details = $this->getCartDetails();

        $data = [
            "cart" => $cart,
            "details" => $details
        ];
        return response($data);
    }
}
