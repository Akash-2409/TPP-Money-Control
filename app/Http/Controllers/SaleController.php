<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Inventory;
use App\Services\InventoryServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryServices $inventoryService)
    {
        // $this->middleware('auth'); // Handled in routes
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $sales = Sale::with(['product', 'party'])->orderBy('date','desc')->paginate(25);
        return view('sales.index', compact('sales'));
    }

    public function invoice(Sale $sale)
    {
        // Group items sold to the same customer on the same date to form a complete invoice
        if ($sale->customer) {
            $invoiceItems = Sale::with('product')
                ->where('date', $sale->date)
                ->where('customer', $sale->customer)
                ->get();
        } else {
            $invoiceItems = collect([$sale]);
        }

        return view('sales.invoice', compact('sale', 'invoiceItems'));
    }

    public function create()
    {
        $products = \App\Models\Product::with('inventory')->orderBy('name')->get();
        $parties = \App\Models\Party::whereIn('type', ['customer', 'both'])->orderBy('name')->get();
        return view('sales.create', compact('products', 'parties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|numeric|min:0.001',
            'rate.*' => 'nullable|numeric',
            'total.*' => 'nullable|numeric',
            'gst_rate.*' => 'nullable|numeric',
            'village' => 'nullable|string',
            'vehicle_no' => 'nullable|string',
            'customer' => 'nullable|string',
            'party_id' => 'nullable|exists:parties,id',
            'note' => 'nullable|string',
            'tax_type' => 'required|string|in:none,igst,cgst_sgst'
        ]);

        foreach ($request->product_id as $key => $product) {
            
            $quantity = $request->quantity[$key];
            $rate = $request->rate[$key] ?? 0;
            $taxable_amount = $quantity * $rate;
            
            $tax_type = $request->tax_type;
            $gst_rate = $request->gst_rate[$key] ?? 0;
            
            $cgst = 0; $sgst = 0; $igst = 0;
            
            if ($tax_type === 'igst') {
                $igst = $taxable_amount * ($gst_rate / 100);
            } elseif ($tax_type === 'cgst_sgst') {
                $half_rate = $gst_rate / 2;
                $cgst = $taxable_amount * ($half_rate / 100);
                $sgst = $taxable_amount * ($half_rate / 100);
            }
            
            $total = $taxable_amount + $cgst + $sgst + $igst;

            Sale::create([
                'product_id' => $product,
                'date' => $request->date,
                'quantity' => $quantity,
                'rate' => $rate,
                'taxable_amount' => $taxable_amount,
                'tax_type' => $tax_type,
                'gst_rate' => $gst_rate,
                'cgst_amount' => $cgst,
                'sgst_amount' => $sgst,
                'igst_amount' => $igst,
                'total' => $total, // Grand Total for row
                'village' => $request->village, // Global field
                'vehicle_no' => $request->vehicle_no, // Global field
                'labour_charge' => 0, // Not used in UI currently
                'customer' => $request->customer,
                'party_id' => $request->party_id,
                'note' => $request->note,
                'user_id' => Auth::id(),
            ]);
        }

        // Recalculate inventory
        foreach (array_unique($request->product_id) as $productId) {
            $this->inventoryService->recalculateForProduct($productId);
        }

        return redirect()
            ->route('sales.index')
            ->with('success','Sale recorded successfully!');
    }
}
