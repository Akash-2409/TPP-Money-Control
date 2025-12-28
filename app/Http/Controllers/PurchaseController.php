<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with('material')->orderBy('date','desc')->paginate(25);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $materials = Material::orderBy('name')->get();
        return view('purchases.create', compact('materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0.001',
            'rate' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string',
            'bill_no' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $amount = $request->rate ? ($request->quantity * $request->rate) : null;

        Purchase::create([
            'material_id' => $request->material_id,
            'user_id' => Auth::id(),
            'date' => $request->date,
            'quantity' => $request->quantity,
            'rate' => $request->rate,
            'amount' => $amount,
            'supplier' => $request->supplier,
            'bill_no' => $request->bill_no,
            'note' => $request->note,
        ]);

        // Update material stock
        $material = Material::find($request->material_id);
        $material->current_stock += $request->quantity;
        $material->save();

        return redirect()->route('purchases.index')->with('success', 'Purchase recorded & stock updated!');
    }
}
