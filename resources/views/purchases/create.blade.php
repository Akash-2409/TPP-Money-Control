@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        <h3 class="mb-4">Add Purchase</h3>

        <div class="card">
            <div class="card-body">

                <form method="POST" action="{{ route('purchases.store') }}">
                    @csrf

                    <div class="row mb-3">

                        <div class="col-md-4">
                            <label>Material</label>
                            <select name="material_id" class="form-control" required>
                                @foreach($materials as $m)
                                <option value="{{ $m->id }}">
                                    {{ ucfirst($m->material_type) }} - {{ $m->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Date</label>
                            <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label>Quantity</label>
                            <input type="number" step="0.001" class="form-control" name="quantity" required>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-4">
                            <label>Rate (₹)</label>
                            <input type="number" step="0.01" class="form-control" name="rate">
                        </div>

                        <div class="col-md-4">
                            <label>Supplier</label>
                            <input type="text" class="form-control" name="supplier">
                        </div>

                        <div class="col-md-4">
                            <label>Bill No</label>
                            <input type="text" class="form-control" name="bill_no">
                        </div>

                    </div>

                    <label>Note</label>
                    <textarea name="note" class="form-control mb-3"></textarea>

                    <button class="btn btn-primary">Save Purchase</button>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection
