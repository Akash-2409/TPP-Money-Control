<?php

namespace App\Http\Controllers;

use App\Models\Party;
// use Illuminate\Http\Request;
use Illuminate\Http\Request;


class PartyController extends Controller
{
    public function index()
    {
        $parties = Party::all();
        return view('parties.index', compact('parties'));
    }

    public function create()
    {
        return view('parties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:customer,supplier,both',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'gst_number' => 'nullable|string|max:50',
            'opening_balance' => 'numeric'
        ]);

        Party::create([
            'name' => $request->name,
            'type' => $request->type,
            'email'=> $request->email,
            'phone'=>$request->phone,
            'billing_address'=>$request->billing_address,
            'shipping_address'=>$request->shipping_address,
            'gst_number'=>$request->gst_number,
            'opening_balance'=>$request->opening_balance
        ]);
        // Party::create($validated);
        return redirect()->route('parties.index')->with('success', 'Party created successfully.');
    }

    public function edit(Party $party)
    {
        return view('parties.edit', compact('party'));
    }

    public function update(Request $request, Party $party)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:customer,supplier,both',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'gst_number' => 'nullable|string|max:50',
            'opening_balance' => 'numeric'
        ]);

        $party->update($validated);
        return redirect()->route('parties.index')->with('success', 'Party updated successfully.');
    }

    public function destroy(Party $party)
    {
        $party->delete();
        return redirect()->route('parties.index')->with('success', 'Party deleted successfully.');
    }

    public function getPartyDetails(Party $party)
    {
        return response()->json($party);
    }
}
