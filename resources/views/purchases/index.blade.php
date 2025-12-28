@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Purchase Records</h3>

    <a class="btn btn-primary mb-3" href="{{ route('purchases.create') }}">Add Purchase</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Date</th>
                <th>Material</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Supplier</th>
                <th>Bill No</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $p)
                <tr>
                    <td>{{ $p->date }}</td>
                    <td>{{ $p->material->name }}</td>
                    <td>{{ $p->quantity }}</td>
                    <td>{{ $p->rate }}</td>
                    <td>{{ $p->amount }}</td>
                    <td>{{ $p->supplier }}</td>
                    <td>{{ $p->bill_no }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $purchases->links() }}

</div>
@endsection
