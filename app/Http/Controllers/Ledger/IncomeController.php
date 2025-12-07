<?php

namespace App\Http\Controllers\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of incomes (search + month filter + pagination).
     */
    public function index(Request $request)
    {
        $q = $request->query('q');           // search text
        $month = $request->query('month');   // YYYY-MM

        $query = Entry::where('type', 'income')->with('user');

        // normal user sees only their data
        if (Auth::user()->role !== 'superadmin') {
            $query->where('user_id', Auth::id());
        }

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhere('amount', 'like', "%{$q}%");
            });
        }

        if ($month) {
            [$y, $m] = explode('-', $month);
            $query->whereYear('date', $y)->whereMonth('date', $m);
        }

        $incomes = $query->orderBy('date', 'desc')->paginate(25)->withQueryString();

        return view('income.index', compact('incomes', 'q', 'month'));
    }

    /**
     * Show income form (NO category).
     */
    public function create()
    {
        return view('income.create');
    }

    /**
     * Store new income.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:2000',
        ]);

        $data['user_id'] = Auth::id();
        $data['type'] = 'income';
        $data['category_id'] = null; // disable category

        Entry::create($data);

        return redirect()->route('incomes.index')->with('success', 'Income added.');
    }

    /**
     * Delete income.
     */
    public function destroy(Entry $income)
    {
        if (Auth::user()->role !== 'superadmin' && $income->user_id !== Auth::id()) {
            abort(403);
        }

        $income->delete();

        return back()->with('success', 'Income removed.');
    }
}
