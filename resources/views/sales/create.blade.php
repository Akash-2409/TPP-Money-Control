@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <h3 class="mb-3">Add Sale / Dispatch</h3>

        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('sales.store') }}">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label>Product</label>
                            <select name="product_id" class="form-control" required>
                                @foreach(\App\Models\Product::all() as $p)
                                    <option value="{{ $p->id }}">{{$p->category}}-{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label>Quantity Sold (kg)</label>
                            <input type="number" step="0.001" name="quantity" class="form-control" required>
                        </div>
                    </div>

                    <label>Customer (optional)</label>
                    <input type="text" name="customer" class="form-control mb-3">

                    <label>Note (optional)</label>
                    <textarea name="note" class="form-control mb-3"></textarea>

                    <button class="btn btn-primary">Save Sale</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
