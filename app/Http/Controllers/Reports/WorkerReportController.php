<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Models\WorkerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Monthly salary report for all workers.
     * Calculates: salary, uddhar, salary paid, remaining.
     */
    public function monthly(Request $request)
    {
        // Default month is current month
        $month = $request->query('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        // Get workers list
        if (Auth::user()->role === 'superadmin') {
            $workers = Worker::with(['transactions'])->orderBy('name')->get();
        } else {
            $workers = Worker::where('created_by', Auth::id())
                ->with('transactions')
                ->orderBy('name')
                ->get();
        }

        $report = [];

        foreach ($workers as $worker) {

            // transactions for this specific month
            $monthlyTransactions = $worker->transactions()
                ->whereYear('date', $year)
                ->whereMonth('date', $mon)
                ->get();

            $uddhar = $monthlyTransactions->where('type','uddhar')->sum('amount');
            $salaryPaid = $monthlyTransactions->where('type','salary_payment')->sum('amount');
            $bonus = $monthlyTransactions->where('type','bonus')->sum('amount');
            $adjustment = $monthlyTransactions->where('type','adjustment')->sum('amount');

            $monthlySalary = $worker->monthly_salary;

            // Calculate remaining salary
            $remaining = $monthlySalary - ($uddhar + $salaryPaid) - $bonus + $adjustment;

            $report[] = [
                'worker' => $worker,
                'monthly_salary' => $monthlySalary,
                'uddhar' => $uddhar,
                'salary_paid' => $salaryPaid,
                'bonus' => $bonus,
                'adjustment' => $adjustment,
                'remaining_salary' => $remaining,
            ];
        }

        return view('reports.workers.monthly', compact('report', 'month'));
    }
}
