<?php

namespace App\Http\Controllers\Admin;

use App\Models\Promo;
use App\Models\Product;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $promos = Promo::with('product')->latest()->paginate(15);
        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        $products = Product::active()->get();
        return view('admin.promos.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'type' => 'required|in:product,general',
            'product_id' => 'nullable|required_if:type,product|exists:products,id',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_price' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promos', 'public');
        }

        Promo::create($data);

        return redirect()->route('admin.promos.index')
                        ->with('success', 'Promo berhasil ditambahkan');
    }

    public function edit(Promo $promo)
    {
        $products = Product::active()->get();
        return view('admin.promos.edit', compact('promo', 'products'));
    }

    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'type' => 'required|in:product,general',
            'product_id' => 'nullable|required_if:type,product|exists:products,id',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_price' => 'nullable|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('promos', 'public');
        }

        $promo->update($data);

        return redirect()->route('admin.promos.index')
                        ->with('success', 'Promo berhasil diperbarui');
    }

    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('admin.promos.index')
                        ->with('success', 'Promo berhasil dihapus');
    }
}
