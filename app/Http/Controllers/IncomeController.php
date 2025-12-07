<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Entry::where('type', 'income')->orderBy('date', 'desc');

        if ($user->role !== 'superadmin') {
            $query->where('user_id', $user->id);
        }

        $incomes = $query->paginate(20);

        return view('income.index', compact('incomes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Entry::create([
            'user_id' => Auth::id(),
            'type' => 'income',
            'amount' => $request->amount,
            'date' => $request->date,
            'category_id' => $request->category_id ?? null,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Income added successfully!');
    }

    public function destroy(Entry $income)
    {
        if (Auth::user()->role !== 'superadmin' && $income->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $income->delete();

        return back()->with('success', 'Income deleted successfully.');
    }
}
