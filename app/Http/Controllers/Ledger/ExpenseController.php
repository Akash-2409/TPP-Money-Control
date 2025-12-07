<?php

namespace App\Http\Controllers\Ledger;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display list of expenses with search, filters and pagination.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');        
        $month = $request->query('month'); 

        $query = Expense::with('user');

        // normal users should see only their own expenses
        if (Auth::user()->role !== 'superadmin') {
            $query->where('user_id', Auth::id());
        }

        // search by description or amount
        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('description','like',"%{$q}%")
                    ->orWhere('amount','like',"%{$q}%");
            });
        }

        // month filter (YYYY-MM)
        if ($month) {
            [$year, $mon] = explode('-', $month);
            $query->whereYear('date', $year)->whereMonth('date', $mon);
        }

        $expenses = $query->orderBy('date','desc')->paginate(25)->withQueryString();
        $categories = Category::where('type','expense')->orderBy('name')->get();

        return view('ledger.expenses.index', compact('expenses','categories','q','month'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $this->authorize('create', Expense::class);
        $categories = Category::where('type','expense')->orderBy('name')->get();
        return view('ledger.expenses.create', compact('categories'));
    }

    /**
     * Store a new expense.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Expense::class);

        $data = $request->validate([
            'date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string|max:2000',
        ]);

        $data['user_id'] = Auth::id();

        Expense::create($data);

        return redirect()->route('expenses.index')->with('success','Expense added.');
    }

    /**
     * Delete an expense.
     */
    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        $expense->delete();

        return back()->with('success','Expense deleted.');
    }
}
