<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkerController extends Controller
{
    public function index()
    {
        $query = Worker::query();

        if (Auth::user()->role !== 'superadmin') {
            $query->where('created_by', Auth::id());
        }

        $workers = $query->orderBy('name')->paginate(20);

        return view('workers.index', compact('workers'));
    }

    public function create()
    {
        return view('workers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'monthly_salary' => 'required|numeric|min:0',
            'contact' => 'nullable|string'
        ]);

        Worker::create([
            'name' => $request->name,
            'monthly_salary' => $request->monthly_salary,
            'contact' => $request->contact,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('workers.index')->with('success','Worker added');
    }
    public function show($id)
    {
        abort(404); // or return a view later
    }
}
