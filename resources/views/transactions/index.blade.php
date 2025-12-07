@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Transaction Entry</h3>
    <div class="alert alert-info">
        <strong>Total Income:</strong> ₹ {{ number_format($totalIncome, 2) }} &nbsp; |
        <strong>Total Expense:</strong> ₹ {{ number_format($totalExpense, 2) }} &nbsp; |

        @if($balance >= 0)
            <strong class="text-success">Balance: ₹ {{ number_format($balance, 2) }}</strong>
        @else
            <strong class="text-danger">Balance: ₹ {{ number_format($balance, 2) }}</strong>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Transaction Form --}}
    <div class="card mb-4">
        <div class="card-header">Add Transaction</div>
        <div class="card-body">
            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf

                <div class="row">

                    <div class="col-md-3">
                        <label>Type</label>
                        <select name="type" class="form-control" required>
                            <option value="income">Income</option>
                            <option value="expense">Expense</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Amount</label>
                        <input type="number" step="0.01" name="amount" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label>Description (Optional)</label>
                        <input type="text" name="description" class="form-control">
                    </div>

                </div>

                <button class="btn btn-primary mt-3">Save</button>
            </form>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="mb-3">
        <div class="row">

            <div class="col-md-3">
                <select name="type" class="form-control">
                    <option value="">All</option>
                    <option value="income" {{ $filter=='income' ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ $filter=='expense' ? 'selected' : '' }}>Expense</option>
                </select>
            </div>

            <div class="col-md-3">
                <input type="month" name="month" class="form-control" value="{{ $month }}">
            </div>

            <div class="col-md-3">
                <input type="text" name="q" class="form-control" placeholder="Search..." value="{{ $search }}">
            </div>

            <div class="col-md-3">
                <button class="btn btn-secondary">Filter</button>
            </div>

        </div>
    </form>

    {{-- Table --}}
    <div class="card">
        <div class="card-header">Recent Transactions</div>

        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($entries as $e)
                    <tr>
                        <td>{{ $e->date }}</td>
                        <td>
                            @if($e->type == 'income')
                                <span class="text-success">Income</span>
                            @else
                                <span class="text-danger">Expense</span>
                            @endif
                        </td>
                        <td>₹ {{ number_format($e->amount,2) }}</td>
                        <td>{{ $e->description }}</td>
                        <td>
                            <form method="POST" action="{{ route('transactions.destroy',$e->id) }}"
                                  onsubmit="return confirm('Delete entry?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No entries yet.</td></tr>
                @endforelse
            </tbody>

        </table>

        <div class="p-3">
            {{ $entries->links() }}
        </div>

    </div>

</div>
@endsection
