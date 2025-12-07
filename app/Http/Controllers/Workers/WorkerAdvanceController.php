<?php

namespace App\Http\Controllers\Workers;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Models\WorkerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerAdvanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List all advances / salary payments.
     * Superadmin: sees all
     * User: sees only workers they created
     */
    public function index(Request $request)
    {
        $q = $request->query('q');
        $worker_id = $request->query('worker');

        $query = WorkerTransaction::with('worker');

        // normal user should see only his workers' transactions
        if (Auth::user()->role !== 'superadmin') {
            $query->whereHas('worker', function($sub) {
                $sub->where('created_by', Auth::id());
            });
        }

        // Filter by worker
        if ($worker_id) {
            $query->where('worker_id', $worker_id);
        }

        // Search by worker name or description
        if ($q) {
            $query->where(function($sub) use ($q) {
                $sub->where('description', 'like', "%{$q}%")
                    ->orWhereHas('worker', function($w) use ($q) {
                        $w->where('name', 'like', "%{$q}%");
                    });
            });
        }

        $transactions = $query->orderBy('date', 'desc')->paginate(20)->withQueryString();

        // for dropdown
        $workers = (Auth::user()->role === 'superadmin')
            ? Worker::orderBy('name')->get()
            : Worker::where('created_by', Auth::id())->orderBy('name')->get();

        return view('workers.advances.index', compact('transactions', 'workers', 'worker_id', 'q'));
    }

    /**
     * Show form for giving advance/salary payment.
     */
    public function create()
    {
        $this->authorize('create', WorkerTransaction::class);

        $workers = (Auth::user()->role === 'superadmin')
            ? Worker::orderBy('name')->get()
            : Worker::where('created_by', Auth::id())->orderBy('name')->get();

        return view('workers.advances.create', compact('workers'));
    }

    /**
     * Save the Uddhar / salary payment / bonus.
     */
    public function store(Request $request)
    {
        $this->authorize('create', WorkerTransaction::class);

        $data = $request->validate([
            'worker_id' => 'required|exists:workers,id',
            'type' => 'required|in:uddhar,salary_payment,bonus,adjustment',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string|max:2000',
        ]);

        // normal user cannot give transaction to other users' workers
        if (Auth::user()->role !== 'superadmin') {
            $workerCheck = Worker::findOrFail($data['worker_id']);
            if ($workerCheck->created_by !== Auth::id()) {
                abort(403, "You cannot add transactions for this worker.");
            }
        }

        WorkerTransaction::create([
            'worker_id' => $data['worker_id'],
            'type' => $data['type'],
            'amount' => $data['amount'],
            'date' => $data['date'],
            'description' => $data['description'] ?? null,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('worker-advances.index')->with('success', 'Transaction recorded.');
    }

    /**
     * Delete a transaction.
     */
    public function destroy(WorkerTransaction $workerTransaction)
    {
        $this->authorize('delete', $workerTransaction);

        // normal user can only delete their own workers' transactions
        if (Auth::user()->role !== 'superadmin') {
            if ($workerTransaction->worker->created_by !== Auth::id()) {
                abort(403, "You cannot delete this record.");
            }
        }

        $workerTransaction->delete();

        return back()->with('success', 'Transaction removed.');
    }
}
