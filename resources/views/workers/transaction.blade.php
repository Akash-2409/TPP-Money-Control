@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Worker Transactions</h3>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Select worker --}}
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <label>Select Worker</label>
                <select name="worker_id" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Select Worker --</option>
                    @foreach($workers as $w)
                        <option value="{{ $w->id }}" {{ request('worker_id') == $w->id ? 'selected':'' }}>
                            {{ $w->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

    @if(isset($worker))

        <h4 class="mb-3">Transactions for: <strong>{{ $worker->name }}</strong></h4>

        {{-- Add new transaction --}}
        <div class="card mb-4">
            <div class="card-header">Add Transaction</div>
            <div class="card-body">

                <form method="POST" action="{{ route('worker.transaction.store') }}">
                    @csrf

                    <input type="hidden" name="worker_id" value="{{ $worker->id }}">

                    <div class="row">

                        <div class="col-md-3">
                            <label>Type</label>
                            <select name="type" class="form-control" required>
                                <option value="uddhar">Uddhar</option>
                                <option value="salary_payment">Salary Payment</option>
                                <option value="bonus">Bonus</option>
                                <option value="adjustment">Adjustment</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Amount</label>
                            <input type="number" name="amount" step="0.01" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label>Note</label>
                            <input type="text" name="note" class="form-control">
                        </div>

                    </div>

                    <button class="btn btn-primary mt-3">Save</button>
                </form>

            </div>
        </div>

        {{-- Transaction history table --}}
        <div class="card">
            <div class="card-header">Transaction History</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                        <tr>
                            <td>{{ $t->date }}</td>
                            <td>
                                @if($t->type == 'uddhar')
                                    <span class="text-danger">Uddhar</span>
                                @elseif($t->type == 'salary_payment')
                                    <span class="text-success">Salary Paid</span>
                                @elseif($t->type == 'bonus')
                                    <span class="text-info">Bonus</span>
                                @else
                                    <span class="text-warning">Adjustment</span>
                                @endif
                            </td>
                            <td>₹ {{ number_format($t->amount, 2) }}</td>
                            <td>{{ $t->note }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="p-3">
                {{ $transactions->links() }}
            </div>
        </div>

    @endif

</div>
@endsection
