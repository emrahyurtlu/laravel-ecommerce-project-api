<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $allCategories = Category::all()->where("is_active", true);

        if ($allCategories->count() == 0) {
            return response($allCategories, 404);
        }

        return response($allCategories);
    }

    public function getCategory(Category $category)
    {
        if ($category == null)
            return response($category, 404);

        return response($category, 201);
    }
}
