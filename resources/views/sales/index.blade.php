@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <h3 class="mb-3">Sales / Dispatch Records</h3>

        <a class="btn btn-primary mb-3" href="{{ route('sales.create') }}">Add Sale</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Qty Sold</th>
                    <th>Customer</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $s)
                    <tr>
                        <td>{{ $s->date }}</td>
                        <td>{{ $s->product->name }}</td>
                        <td>{{ $s->quantity }}</td>
                        <td>
                            @if($s->party)
                                <span class="text-primary font-weight-bold"><a href="{{ route('parties.edit', $s->party_id) }}">{{ $s->party->name }}</a></span>
                            @else
                                {{ $s->customer }}
                            @endif
                        </td>
                        <td>{{ $s->note }}</td>
                        <td>
                            <a href="{{ route('sales.invoice', $s->id) }}" class="btn btn-sm btn-info text-white" target="_blank" title="Print Invoice">
                                <i class="mdi mdi-printer"></i> Print Invoice
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $sales->links() }}
    </div>
</div>
@endsection
