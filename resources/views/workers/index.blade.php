@extends('layouts.app')

@section('content')
<div class="container">

    <h3>Workers</h3>

    <a href="{{ route('workers.create') }}" class="btn btn-primary mb-3">Add Worker</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Monthly Salary</th>
                <th>Remaining Salary</th>
                <th>Uddhar</th>     
                <th>Contact</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach($workers as $w)
                @php
                    $uddhar = $w->transactions()->where('type','uddhar')->sum('amount');
                    $paid = $w->transactions()->where('type','salary_payment')->sum('amount');
                    $remaining = $w->monthly_salary - $uddhar - $paid;
                @endphp

                <tr>
                    <td>{{ $w->name }}</td>
                    <td>₹ {{ number_format($w->monthly_salary,2) }}</td>
                    <td class="{{ $remaining >= 0 ? 'text-success':'text-danger' }}">
                        ₹ {{ number_format($remaining,2) }}
                    </td>
                    <td class="text-danger">
                        ₹ {{ number_format($uddhar,2) }}
                    </td>
                    <td>{{ $w->contact }}</td>
                    <td>
                        {{-- Add Uddhar / Salary buttons --}}
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addTx{{ $w->id }}">Add Transaction</button>
                    </td>
                </tr>

                {{-- Transaction Modal --}}
                
            @endforeach
        </tbody>

    </table>


    @foreach($workers as $w)
    <div class="modal fade" id="addTx{{ $w->id }}">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('worker.transaction.store') }}">
                            @csrf
                            <input type="hidden" name="worker_id" value="{{ $w->id }}">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5>Add Transaction for {{ $w->name }}</h5>
                                </div>
                                <div class="modal-body">

                                    <label>Type</label>
                                    <select name="type" class="form-control">
                                        <option value="uddhar">Uddhar</option>
                                        <option value="salary_payment">Salary Payment</option>
                                        <option value="bonus">Bonus</option>
                                        <option value="adjustment">Adjustment</option>
                                    </select>

                                    <label class="mt-2">Amount</label>
                                    <input type="number" name="amount" class="form-control">

                                    <label class="mt-2">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">

                                    <label class="mt-2">Note</label>
                                    <textarea name="description" class="form-control"></textarea>

                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-success">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
    @endforeach

    {{ $workers->links() }}

</div>
@endsection
