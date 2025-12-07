<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show all products with search and pagination.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Product::with('inventory');

        // search by product name
        if ($q) {
            $query->where('name', 'like', "%{$q}%");
        }

        $products = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('inventory.products.index', compact('products', 'q'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $this->authorize('create', Product::class);
        return view('inventory.products.create');
    }

    /**
     * Store a new product + create inventory row.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Product::class);
        // dd($request->all());
        $data = $request->validate([
            'main_product'  => 'required|string|max:255',
            'category'      => 'required|string|max:255',
            'name'          => 'required|string|max:191',
            'unit'          => 'nullable|string|max:20',
            'opening_stock' => 'nullable|numeric|min:0',
        ]);

        // Create product
        $product = Product::create([
            'main_product' => $data['main_product'],
            'category'     => $data['category'],
            'name'         => $data['name'],
            'unit'         => $data['unit'] ?? 'kg',
        ]);

        // Create inventory entry
        Inventory::create([
            'product_id'     => $product->id,
            'opening_stock'  => $data['opening_stock'] ?? 0,
            'total_produced' => 0,
            'total_sold'     => 0,
            'current_stock'  => $data['opening_stock'] ?? 0,
        ]);

        return redirect()->route('products.index')->with('success', 'Product added.');
    }


    /**
     * Show edit form.
     */
    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return view('inventory.products.edit', compact('product'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $data = $request->validate([
            'main_product' => 'required|string|max:255',
            'category'     => 'required|string|max:255',
            'name'         => 'required|string|max:191',
            'unit'         => 'nullable|string|max:20',
        ]);

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }


    /**
     * Delete product & inventory.
     */
    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        // Inventory entry will be deleted automatically (cascade)
        $product->delete();

        return back()->with('success', 'Product removed.');
    }
}
