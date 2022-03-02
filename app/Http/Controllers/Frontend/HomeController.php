<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::all()->where("is_active", true);
        $categories = Category::all()->where("is_active", true);

        $data = [
            "products" => $products,
            "categories" => $categories
        ];

        return response($data);
    }
}
