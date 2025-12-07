<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entry;
// use App\Models\Expense;
use App\Models\DailyProduction;
use App\Models\WorkerTransaction;
use App\Models\Worker;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $month = now()->format('m');
        $year = now()->format('Y');

        /* ----------------------------------------------------
            FILTER SCOPE: Superadmin sees ALL data,
            other users see only their data.
        ----------------------------------------------------- */

        /* ------------------------------------------
            Income (entry.type = income)
        -------------------------------------------*/
        $incomeQuery = Entry::where('type', 'income')
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        if ($user->role !== 'superadmin') {
            $incomeQuery->where('user_id', $user->id);
        }

        $monthlyIncome = $incomeQuery->sum('amount');

        /* ------------------------------------------
            Expense (entry.type = expense)
        -------------------------------------------*/
        $expenseQuery = Entry::where('type', 'expense')
            ->whereYear('date', $year)
            ->whereMonth('date', $month);

        if ($user->role !== 'superadmin') {
            $expenseQuery->where('user_id', $user->id);
        }

        $monthlyExpense = $expenseQuery->sum('amount');

        // Daily production (current month)
        $prodQuery = DailyProduction::whereYear('date', $year)->whereMonth('date', $month);
        if ($user->role !== 'superadmin') {
            $prodQuery->where('user_id', $user->id);
        }
        $monthlyProduction = $prodQuery->sum('production_qty');

        // Worker transactions (uddhar & salary paid)
        $workerTxQuery = WorkerTransaction::whereYear('date', $year)->whereMonth('date', $month);
        if ($user->role !== 'superadmin') {
            $workerTxQuery->whereHas('worker', function($q) use ($user) {
                $q->where('created_by', $user->id);
            });
        }

        $monthlyUddhar = $workerTxQuery->where('type', 'uddhar')->sum('amount');
        $monthlySalaryPaid = WorkerTransaction::whereYear('date',$year)
            ->whereMonth('date',$month)
            ->where('type','salary_payment')
            ->when($user->role !== 'superadmin', function($q) use ($user) {
                $q->whereHas('worker', function($sub) use ($user){
                    $sub->where('created_by', $user->id);
                });
            })
            ->sum('amount');

        // Worker count
        $workerCount = ($user->role === 'superadmin')
            ? Worker::count()
            : Worker::where('created_by', $user->id)->count();

        // Balance for this month
        $balance = $monthlyIncome - $monthlyExpense;

        $daysInMonth = now()->daysInMonth;

        $chartIncome = [];
        $chartExpense = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {

            $date = now()->setDay($i)->format('Y-m-d');

            $chartIncome[] = \App\Models\Entry::where('type', 'income')
                ->whereDate('date', $date)
                ->sum('amount');

            $chartExpense[] = \App\Models\Entry::where('type', 'expense')
                ->whereDate('date', $date)
                ->sum('amount');
        }
        $chartProduction = DailyProduction::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->groupBy('date')
            ->orderBy('date')
            ->selectRaw('date, SUM(production_qty) as qty')
            ->get();


            return view('dashboard.index', compact(
                'monthlyIncome',
                'monthlyExpense',
                'balance',
                'monthlyProduction',
                'workerCount',
                'monthlyUddhar',
                'monthlySalaryPaid',
                'chartIncome',
                'chartExpense',
                'chartProduction'
            ));
            


    }
}
