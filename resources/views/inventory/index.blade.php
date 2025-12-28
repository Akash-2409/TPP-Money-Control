@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Inventory</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Adjust Stock --}}
    <div class="card mb-4">
        <div class="card-header">Adjust Stock</div>
        <div class="card-body">

            <form method="POST" action="{{ route('inventory.adjust') }}">
                @csrf

                <div class="row">

                    <div class="col-md-4">
                        <label>Product</label>
                        <select name="product_id" class="form-control">
                            @foreach(App\Models\Product::orderBy('name')->get() as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label>Adjustment (kg)</label>
                        <input type="number" step="0.001" name="amount" class="form-control">
                    </div>

                    <div class="col-md-4 mt-4">
                        <button class="btn btn-primary">Apply</button>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- Inventory Table --}}
    <div class="card">
        <div class="card-header">Stock Summary</div>

        <table class="table">
            <thead>
                <tr>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Opening</th>
                    <th>Produced</th>
                    <th>Sold</th>
                    <th>Current Stock</th>
                </tr>
            </thead>

            <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->product->category }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->opening_stock }}</td>
                        <td>{{ $item->total_produced }}</td>
                        <td>{{ $item->total_sold }}</td>
                        <td><strong>{{ $item->current_stock }}</strong></td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <div class="p-3">
            {{ $items->links() }}
        </div>

    </div>

</div>
@endsection
