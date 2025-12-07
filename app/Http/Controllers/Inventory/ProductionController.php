<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\DailyProduction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\InventoryServices;

class ProductionController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryServices $inventoryService)
    {
        $this->middleware('auth');
        $this->inventoryService = $inventoryService;
    }

    /**
     * List daily production records (with filters, pagination).
     */
    public function index(Request $request)
    {
        $month = $request->query('month');
        $q = $request->query('q');

        $query = DailyProduction::with('product', 'user');

        if (Auth::user()->role !== 'superadmin') {
            $query->where('user_id', Auth::id());
        }

        if ($month) {
            [$y, $m] = explode('-', $month);
            $query->whereYear('date', $y)->whereMonth('date', $m);
        }

        if ($q) {
            $query->whereHas('product', function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%");
            });
        }

        $productions = $query->orderBy('date', 'desc')->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('productions.index', compact('productions', 'products', 'month', 'q'));
    }

    /**
     * Store a new production entry.
     */
    public function store(Request $request)
    {
        
        // $this->authorize('create', DailyProduction::class);
        // dd($request->all());
        
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'production_qty' => 'required|numeric|min:0',
            'date' => 'required|date',
            'note' => 'nullable|string|max:2000',
        ]);
        DailyProduction::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'production_qty' => $request->production_qty,
            'date' => $request->date,
            'note' => $request->note,
            // 'category_id' => null // never used now
        ]);


        // Update inventory for this product
        $this->inventoryService->recalculateForProduct($data['product_id']);

        return back()->with('success', 'Production added successfully.');
    }

    /**
     * Delete a production entry.
     */
    public function destroy(DailyProduction $production)
    {
        $this->authorize('delete', $production);

        $productId = $production->product_id;
        $production->delete();

        // Update inventory after deletion
        $this->inventoryService->recalculateForProduct($productId);

        return back()->with('success', 'Production removed.');
    }
}
