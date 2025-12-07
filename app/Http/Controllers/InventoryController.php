<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $items = Inventory::with('product')->paginate(20);
        return view('inventory.index', compact('items'));
    }

    public function adjust(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'amount' => 'required|numeric',
        ]);

        $inv = Inventory::firstOrCreate(['product_id' => $request->product_id]);

        $inv->current_stock += $request->amount;
        $inv->save();

        return back()->with('success', 'Stock adjusted.');
    }
}
