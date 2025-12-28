<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with('product')->orderBy('date','desc')->paginate(25);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        return view('sales.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0.001',
            'customer' => 'nullable|string',
            'note' => 'nullable|string'
        ]);

        $data['user_id'] = Auth::id();

        Sale::create($data);

        // update inventory automatically
        $inv = Inventory::firstOrCreate(['product_id' => $request->product_id]);
        $inv->total_sold += $request->quantity;
        $inv->current_stock -= $request->quantity;
        $inv->save();

        return redirect()->route('sales.index')->with('success','Sale recorded & inventory updated!');
    }
}
