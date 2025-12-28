<?php

namespace App\Http\Controllers;

use App\Models\Entry;
use App\Models\Worker;
use App\Models\WorkerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('type'); // income, expense, or null
        $month = $request->query('month'); // YYYY-MM
        $search = $request->query('q');

        $query = Entry::where('user_id', Auth::id());
        $workers = Worker::select('id','name')->get();

        if ($filter) {
            $query->where('type', $filter);
        }

        if ($month) {
            [$y, $m] = explode('-', $month);
            $query->whereYear('date', $y)->whereMonth('date', $m);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%$search%")
                  ->orWhere('amount', 'like', "%$search%");
            });
        }
        $totalIncome = (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $query)->where('type', 'expense')->sum('amount');

        $balance = $totalIncome - $totalExpense;

        $entries = $query->orderBy('date', 'desc')->paginate(25);

        return view('transactions.index', compact('entries', 'filter', 'month', 'search','totalIncome',
    'totalExpense','workers',
    'balance'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'type' => 'required|in:income,expense',
    //         'amount' => 'required|numeric|min:0.01',
    //         'date' => 'required|date',
    //         'description' => 'nullable|string|max:2000',
    //     ]);

    //     Entry::create([
    //         'user_id' => Auth::id(),
    //         'type' => $request->type,
    //         'amount' => $request->amount,
    //         'date' => $request->date,
    //         'description' => $request->description,
    //         'category_id' => null // never used now
    //     ]);

    //     return back()->with('success', 'Transaction added.');
    // }
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'expense_category' => 'nullable|string',
            'worker_id' => 'nullable|exists:workers,id',
            'description' => 'nullable|string|max:2000',
        ]);
    
        $entry = Entry::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
            'category_id' => null,
        ]);
    
        // 🔥 Worker Udhaar Logic
        if (
            $request->type === 'expense' &&
            $request->expense_category === 'worker_udhaar' &&
            $request->worker_id
        ) {
            WorkerTransaction::create([
                'worker_id' => $request->worker_id,
                'date' => $request->date,
                'amount' => $request->amount,
                'note' => $request->description ?? 'Udhaar added from expense entry',
                'created_by' => Auth::id(),
            ]);
        }
    
        return back()->with('success', 'Transaction added.');
    }
    

    public function destroy(Entry $entry)
    {
        if ($entry->user_id !== Auth::id()) {
            abort(403);
        }

        $entry->delete();

        return back()->with('success', 'Entry deleted.');
    }
}
