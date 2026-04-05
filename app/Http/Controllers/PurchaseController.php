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
        $purchases = Purchase::with(['material', 'party'])->orderBy('date','desc')->paginate(25);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $materials = Material::orderBy('name')->get();
        $parties = \App\Models\Party::whereIn('type', ['supplier', 'both'])->orderBy('name')->get();
        return view('purchases.create', compact('materials', 'parties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'date' => 'required|date',
            'quantity' => 'required|numeric|min:0.001',
            'rate' => 'nullable|numeric|min:0',
            'supplier' => 'nullable|string',
            'party_id' => 'nullable|exists:parties,id',
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
            // Fallback for supplier name or selected party_id
            'supplier' => $request->supplier,
            'party_id' => $request->party_id,
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
