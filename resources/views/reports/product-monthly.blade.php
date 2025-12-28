@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4">Product Monthly Report</h3>

    {{-- Filters --}}
    <form method="GET" class="mb-4">
        <div class="row">

            <div class="col-md-3">
                <label>Month</label>
                <input type="month" name="month" value="{{ $month }}" class="form-control" onchange="this.form.submit()">
            </div>

            <div class="col-md-3">
                <label>Product</label>
                <select name="product_id" class="form-control" onchange="this.form.submit()">
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" {{ $productId == $p->id ? 'selected':'' }}>
                            {{ $p->category }}-{{ $p->name }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>
    </form>

    @if($report)

    {{-- Summary Cards --}}
    <div class="row mb-4">

        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6>Opening Stock</h6>
                    <h3>{{ number_format($report['openingStock'],2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6>Total Produced</h6>
                    <h3>{{ number_format($report['totalProduced'],2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6>Total Sold</h6>
                    <h3>{{ number_format($report['totalSold'],2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6>Closing Stock</h6>
                    <h3>{{ number_format($report['closingStock'],2) }}</h3>
                </div>
            </div>
        </div>

    </div>

    {{-- Charts --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="card mb-4">
        <div class="card-header">Daily Production</div>
        <div class="card-body">
            <canvas id="prodChart"></canvas>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Daily Sales</div>
        <div class="card-body">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <script>
        new Chart(document.getElementById('prodChart'), {
            type: 'bar',
            data: {
                labels: @json($report['dailyProduction']->pluck('date')),
                datasets: [{
                    label: 'Production ',
                    data: @json($report['dailyProduction']->pluck('qty')),
                    backgroundColor: 'blue',
                    maxBarThickness: 30,
                    barThickness: 20
                }]
            }
        });

        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: @json($report['dailySales']->pluck('date')),
                datasets: [{
                    label: 'Sales ',
                    data: @json($report['dailySales']->pluck('qty')),
                    backgroundColor: 'red',
                    maxBarThickness: 30,
                    barThickness: 20
                }]
            }
        });
    </script>

    @endif

</div>
@endsection
