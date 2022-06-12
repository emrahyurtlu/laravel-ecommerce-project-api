<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetails;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class CartController extends Controller
{
    public function index()
    {
        $cart = $this->getOrCreateCart();
        return response($cart);
    }

    /**
     *
     * Lists the cart content
     *
     * @return Builder|Model|object
     */
    private function getOrCreateCart()
    {
        $token = PersonalAccessToken::findToken(request()->bearerToken());
        $user = $token->tokenable()->first();

        $cart = Cart::firstOrCreate(
            ['user_id' => $user->user_id, 'is_active' => true],
            ['code' => Str::random(8)]
        );

        $eagerCart = Cart::with("details")->where("cart_id", $cart->cart_id)->first();
        return $eagerCart;
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
        $quantity = $request->get("quantity", 1);

        $cart = $this->getOrCreateCart();

        $cart->details()->create([
            "product_id" => $product_id,
            "quantity" => $quantity,
        ]);

        return response($cart);
    }

    /**
     *
     * Remove cart detail from cart
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function remove(Request $request)
    {
        $cartDetailId = $request->get("cart_detail_id");
        CartDetails::find($cartDetailId)->delete();

        $cart = $this->getOrCreateCart();

        return response($cart);
    }
}
