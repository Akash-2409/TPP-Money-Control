@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Monthly Salary Summary</h3>

    {{-- Month Selector --}}
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label>Select Month</label>
                <input type="month" name="month" value="{{ $month }}" class="form-control" onchange="this.form.submit()">
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-header">Salary Summary for {{ $month }}</div>

        <table class="table">
            <thead>
                <tr>
                    <th>Worker</th>
                    <th>Monthly Salary</th>
                    <th>Uddhar</th>
                    <th>Salary Paid</th>
                    <th>Bonus</th>
                    <th>Adjustment</th>
                    <th>Remaining Salary</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                @foreach($report as $item)
                    <tr>
                        <td>{{ $item['worker']->name }}</td>
                        <td>₹ {{ number_format($item['worker']->monthly_salary, 2) }}</td>
                        <td class="text-danger">₹ {{ number_format($item['uddhar'], 2) }}</td>
                        <td class="text-success">₹ {{ number_format($item['salary_paid'], 2) }}</td>
                        <td class="text-info">₹ {{ number_format($item['bonus'], 2) }}</td>
                        <td class="text-warning">₹ {{ number_format($item['adjustment'], 2) }}</td>
                        
                        {{-- Remaining Salary --}}
                        <td>
                            @if($item['remaining'] >= 0)
                                <span class="text-success">₹ {{ number_format($item['remaining'], 2) }}</span>
                            @else
                                <span class="text-danger">₹ {{ number_format($item['remaining'], 2) }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" 
                                data-bs-target="#history{{ $item['worker']->id }}">
                                Show
                            </button>
                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>
        @foreach($report as $item)

            @php
                $worker = $item['worker'];

                // Fetch all transactions for the selected month
                $transactions = $worker->transactions()
                    ->whereYear('date', explode('-', $month)[0])
                    ->whereMonth('date', explode('-', $month)[1])
                    ->orderBy('date', 'asc')
                    ->get();
            @endphp

            <div class="modal fade" id="history{{ $worker->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5>Salary & Uddhar History — {{ $worker->name }}</h5>
                            <button class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            @if($transactions->count() == 0)
                                <p class="text-muted">No transactions for this month.</p>
                            @else
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Note</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($transactions as $tx)
                                            <tr>
                                                <td>{{ $tx->date }}</td>
                                                <td>
                                                    @if($tx->type == 'uddhar')
                                                        <span class="badge bg-danger">Uddhar</span>
                                                    @elseif($tx->type == 'salary_payment')
                                                        <span class="badge bg-success">Salary Paid</span>
                                                    @elseif($tx->type == 'bonus')
                                                        <span class="badge bg-info">Bonus</span>
                                                    @elseif($tx->type == 'adjustment')
                                                        <span class="badge bg-warning">Adjustment</span>
                                                    @endif
                                                </td>
                                                <td>₹ {{ number_format($tx->amount, 2) }}</td>
                                                <td>{{ $tx->description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>

            @endforeach

    </div>

</div>
@endsection
