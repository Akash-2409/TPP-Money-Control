@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Stock Ledger</h3>

    {{-- Filters --}}
    <form method="GET" class="mb-3">
        <div class="row">

            <div class="col-md-3">
                <label>Month</label>
                <input type="month" name="month" class="form-control" value="{{ $month }}" onchange="this.form.submit()">
            </div>

            <div class="col-md-3">
                <label>Product</label>
                <select name="product_id" class="form-control" onchange="this.form.submit()">
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ $productId == $p->id ? 'selected':'' }}>
                            {{$p->category}}-{{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </form>

    <div class="card">
        <div class="card-header">Daily Stock Movement</div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Opening</th>
                    <th>Production</th>
                    <th>Sales</th>
                    <th>Closing</th>
                </tr>
            </thead>
            <tbody>

                @forelse($ledger as $row)
                    <tr>
                        <td>{{ $row['date'] }}</td>
                        <td>{{ $row['opening'] }}</td>
                        <td class="text-success">{{ $row['production'] }}</td>
                        <td class="text-danger">{{ $row['sales'] }}</td>
                        <td class="fw-bold">{{ $row['closing'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No data.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>
@endsection
