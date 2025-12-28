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
            <!-- <form method="POST" action="{{ route('production.store') }}">
                @csrf

                <div class="row">

                    <div class="col-md-4">
                        <label>Product</label>
                        <select name="product_id" class="form-control" required>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{$p->category}}-{{ $p->name }} ({{ $p->unit }})</option>
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

            </form> -->
            <form method="POST" action="{{ route('production.store') }}">
                @csrf

                {{-- Date --}}
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Date</label>
                        <input type="date" name="date" class="form-control"
                            value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                {{-- Multiple Products --}}
                <div id="production-rows">

                    <div class="row mb-2 production-row">
                        <div class="col-md-5">
                            <label>Product</label>
                            <select name="product_id[]" class="form-control" required>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->category }} - {{ $p->name }} ({{ $p->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Quantity</label>
                            <input type="number" step="0.001"
                                name="production_qty[]" class="form-control" required>
                        </div>

                        <div class="col-md-2 mt-4">
                            <button type="button"
                                    class="btn btn-danger remove-row">X</button>
                        </div>
                    </div>

                </div>

                {{-- Add More Button --}}
                <button type="button" class="btn btn-secondary mb-3" id="add-row">
                    + Add Product
                </button>

                {{-- Note --}}
                <div class="mb-3">
                    <label>Note (Optional)</label>
                    <textarea name="note" class="form-control"></textarea>
                </div>

                <button class="btn btn-primary">Save Production</button>
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

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th style="width: 50px;"></th>
                <th>Date</th>
                <th>Total Items</th>
            </tr>
        </thead>

        <tbody>
        @foreach($productions as $date => $items)
            {{-- Date Row --}}
            <tr data-bs-toggle="collapse"
                data-bs-target="#prod-{{ $loop->index }}"
                style="cursor:pointer;">
                <td class="text-center">
                    ▶
                </td>
                <td><strong>{{ $date }}</strong></td>
                <td>{{ $items->count() }}</td>
            </tr>

            {{-- Expandable Details --}}
            <tr class="collapse" id="prod-{{ $loop->index }}">
                <td colspan="3">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>User</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $prod)
                                <tr>
                                    <td>{{ $prod->product->category }}</td>
                                    <td>{{ $prod->product->name }}</td>
                                    <td>
                                        {{ $prod->production_qty }}
                                        {{ $prod->product->unit }}
                                    </td>
                                    <td>{{ $prod->user->name }}</td>
                                    <td>{{ $prod->note }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


    <script>
        document.getElementById('add-row').addEventListener('click', function () {

            let container = document.getElementById('production-rows');
            let row = document.querySelector('.production-row').cloneNode(true);

            row.querySelectorAll('input').forEach(input => input.value = '');
            container.appendChild(row);
        });

        document.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                let rows = document.querySelectorAll('.production-row');
                if (rows.length > 1) {
                    e.target.closest('.production-row').remove();
                }
            }
        });
    </script>

</div>
@endsection
