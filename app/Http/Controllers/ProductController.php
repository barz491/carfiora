<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Favorite;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::active()->get();
        
        $query = Product::active()->inStock();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('sort')) {
            match ($request->sort) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'newest' => $query->latest(),
                'bestseller' => $query->where('is_bestseller', true),
                default => $query->latest(),
            };
        }

        $products = $query->paginate(12);

        return view('products.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active || $product->isOutOfStock()) {
            abort(404);
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    public function toggleFavorite(Product $product)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $isFavorited = Favorite::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->exists();

        if ($isFavorited) {
            Favorite::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->delete();
            $message = 'Dihapus dari favorit';
        } else {
            Favorite::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);
            $message = 'Ditambahkan ke favorit';
        }

        return response()->json([
            'message' => $message,
            'isFavorited' => !$isFavorited,
        ]);
    }
}
