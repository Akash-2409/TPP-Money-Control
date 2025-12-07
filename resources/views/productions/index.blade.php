@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Daily Production</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Production Form --}}
    <div class="card mb-4">
        <div class="card-header">Add Production</div>

        <div class="card-body">
            <form method="POST" action="{{ route('production.store') }}">
                @csrf

                <div class="row">

                    <div class="col-md-4">
                        <label>Product</label>
                        <select name="product_id" class="form-control" required>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->unit }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Quantity</label>
                        <input type="number" step="0.001" name="production_qty" class="form-control" required>
                    </div>

                    <div class="col-md-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-2 mt-4">
                        <button class="btn btn-primary">Save</button>
                    </div>

                </div>

                <div class="mt-3">
                    <label>Note (Optional)</label>
                    <textarea name="note" class="form-control"></textarea>
                </div>

            </form>
        </div>
    </div>

    {{-- Filter --}}
    <form method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-3">
                <input type="month" name="month" class="form-control" value="{{ $month }}">
            </div>

            <div class="col-md-3">
                <button class="btn btn-secondary">Filter</button>
            </div>
        </div>
    </form>

    {{-- Production Table --}}
    <div class="card">
        <div class="card-header">Production History</div>

        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>User</th>
                    <th>Note</th>
                </tr>
            </thead>

            <tbody>
                @foreach($productions as $prod)
                    <tr>
                        <td>{{ $prod->date }}</td>
                        <td>{{ $prod->product->name }}</td>
                        <td>{{ $prod->production_qty }} {{ $prod->product->unit }}</td>
                        <td>{{ $prod->user->name }}</td>
                        <td>{{ $prod->note }}</td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <div class="p-3">
            {{ $productions->links() }}
        </div>

    </div>

</div>
@endsection
