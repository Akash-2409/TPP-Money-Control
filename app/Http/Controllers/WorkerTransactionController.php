<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\WorkerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerTransactionController extends Controller
{
    public function transactionsPage(Request $request)
    {
        $workers = Worker::orderBy('name')->get();

        $worker = null;
        $transactions = collect([]);

        if ($request->worker_id) {
            $worker = Worker::find($request->worker_id);
            $transactions = $worker->transactions()->orderBy('date', 'desc')->paginate(20);
        }

        return view('workers.transactions', compact('workers', 'worker', 'transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'type' => 'required|in:uddhar,salary_payment,bonus,adjustment',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string'
        ]);

        WorkerTransaction::create([
            'worker_id' => $request->worker_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description
        ]);

        return back()->with('success','Transaction recorded');
    }

    public function monthlyReport(Request $request)
    {
        $workers = Worker::orderBy('name')->get();

        // Default: current month
        $month = $request->month ?? date('Y-m');
        [$year, $mon] = explode('-', $month);

        $report = [];

        foreach ($workers as $worker) {

            $uddhar = $worker->transactions()
                ->where('type', 'uddhar')
                ->whereYear('date', $year)
                ->whereMonth('date', $mon)
                ->sum('amount');

            $salaryPaid = $worker->transactions()
                ->where('type', 'salary_payment')
                ->whereYear('date', $year)
                ->whereMonth('date', $mon)
                ->sum('amount');

            $bonus = $worker->transactions()
                ->where('type', 'bonus')
                ->whereYear('date', $year)
                ->whereMonth('date', $mon)
                ->sum('amount');

            $adjustment = $worker->transactions()
                ->where('type', 'adjustment')
                ->whereYear('date', $year)
                ->whereMonth('date', $mon)
                ->sum('amount');

            $remainingSalary = 
                $worker->monthly_salary 
                - $uddhar 
                - $salaryPaid 
                + $bonus 
                + $adjustment;

            $report[] = [
                'worker'          => $worker,
                'uddhar'          => $uddhar,
                'salary_paid'     => $salaryPaid,
                'bonus'           => $bonus,
                'adjustment'      => $adjustment,
                'remaining'       => $remainingSalary
            ];
        }

        return view('workers.monthly', compact('report', 'month'));
    }
    
}
