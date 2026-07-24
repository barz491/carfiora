<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Promo;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $promos = Promo::active()->get();
        $bestSellers = Product::active()->bestseller()->inStock()->get();
        $newProducts = Product::active()->new()->inStock()->get();
        $categories = Category::active()->get();
        $totalOrders = Order::count();

        return view('home', compact('promos', 'bestSellers', 'newProducts', 'categories', 'totalOrders'));
    }
}
