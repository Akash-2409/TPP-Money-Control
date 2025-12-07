<?php

namespace App\Http\Controllers\Workers;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show worker list (superadmin sees all, user sees their own).
     */
    public function index(Request $request)
    {
        $query = Worker::query();

        // Normal user sees only workers they created
        if (Auth::user()->role !== 'superadmin') {
            $query->where('created_by', Auth::id());
        }

        // Search (by name)
        if ($search = $request->query('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        $workers = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('workers.index', compact('workers'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $this->authorize('create', Worker::class);
        return view('workers.create');
    }

    /**
     * Store new worker.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Worker::class);

        $data = $request->validate([
            'name' => 'required|string|max:191',
            'monthly_salary' => 'required|numeric|min:0',
            'contact' => 'nullable|string|max:20',
        ]);

        $data['created_by'] = Auth::id();

        Worker::create($data);

        return redirect()->route('workers.index')
            ->with('success', 'Worker added successfully.');
    }

    /**
     * Show edit form.
     */
    public function edit(Worker $worker)
    {
        $this->authorize('update', $worker);
        return view('workers.edit', compact('worker'));
    }

    /**
     * Update worker.
     */
    public function update(Request $request, Worker $worker)
    {
        $this->authorize('update', $worker);

        $data = $request->validate([
            'name' => 'required|string|max:191',
            'monthly_salary' => 'required|numeric|min:0',
            'contact' => 'nullable|string|max:20',
        ]);

        $worker->update($data);

        return back()->with('success', 'Worker updated.');
    }

    /**
     * Delete worker.
     */
    public function destroy(Worker $worker)
    {
        $this->authorize('delete', $worker);

        // Transactions will be deleted automatically by cascade
        $worker->delete();

        return back()->with('success', 'Worker removed.');
    }
}
