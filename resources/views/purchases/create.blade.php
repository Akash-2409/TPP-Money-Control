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
                            <input type="number" step="0.001" class="form-control" name="quantity" id="pur_qty" required>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-md-4">
                            <label>Rate (₹)</label>
                            <input type="number" step="0.01" class="form-control" name="rate" id="pur_rate">
                        </div>

                        <div class="col-md-4">
                            <label>Total Amount (₹)</label>
                            <input type="text" class="form-control text-success font-weight-bold" id="pur_total" readonly>
                        </div>

                        <div class="col-md-4">
                            <label>Supplier Party</label>
                            <select name="party_id" class="form-control mb-2">
                                <option value="">Select Supplier Party...</option>
                                @isset($parties)
                                    @foreach($parties as $party)
                                        <option value="{{ $party->id }}">{{ $party->name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                            <input type="text" class="form-control" name="supplier" placeholder="Or manual name">
                        </div>

                    </div>

                    <div class="row mb-3">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const qtyInput = document.getElementById('pur_qty');
        const rateInput = document.getElementById('pur_rate');
        const totalInput = document.getElementById('pur_total');

        function calculateTotal() {
            let qty = parseFloat(qtyInput.value) || 0;
            let rate = parseFloat(rateInput.value) || 0;
            let total = qty * rate;
            totalInput.value = '₹ ' + total.toFixed(2);
        }

        qtyInput.addEventListener('input', calculateTotal);
        rateInput.addEventListener('input', calculateTotal);
    });
</script>
</div>
@endsection
