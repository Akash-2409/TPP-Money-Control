
@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        {{-- =======================
            TOP SUMMARY CARDS
        ========================--}}
        <div class="row">

            {{-- Income --}}
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-hover">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h3 class="mb-0">₹ {{ number_format($monthlyIncome,2) }}</h3>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-success">
                                    <span class="mdi mdi-cash-multiple icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Income (This Month)</h6>
                    </div>
                </div>
            </div>

            {{-- Expense --}}
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-hover">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h3 class="mb-0">₹ {{ number_format($monthlyExpense,2) }}</h3>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-danger">
                                    <span class="mdi mdi-cash-remove icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Expense (This Month)</h6>
                    </div>
                </div>
            </div>

            {{-- Net Profit --}}
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-hover">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h3 class="mb-0">₹ {{ number_format($netProfit,2) }}</h3>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-info">
                                    <span class="mdi mdi-chart-line icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Net Profit</h6>
                    </div>
                </div>
            </div>

            {{-- Production --}}
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card card-hover">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h3 class="mb-0">{{ number_format($monthlyProduction,2) }} kg</h3>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-warning">
                                    <span class="mdi mdi-factory icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Total Production</h6>
                    </div>
                </div>
            </div>

        </div>


        {{-- =======================
            SECOND ROW
        ========================--}}
        <div class="row">

            <div class="col-md-4 grid-margin stretch-card">
                <div class="card card-hover">
                    <div class="card-body text-center">
                        <h5>Workers</h5>
                        <h2>{{ $workerCount }}</h2>
                        <i class="mdi mdi-account-group icon-lg text-info"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 grid-margin stretch-card">
                <div class="card card-hover">
                    <div class="card-body text-center">
                        <h5>Uddhar (This Month)</h5>
                        <h2>₹ {{ number_format($monthlyUddhar,2) }}</h2>
                        <i class="mdi mdi-alert-circle icon-lg text-danger"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 grid-margin stretch-card">
                <div class="card card-hover">
                    <div class="card-body text-center">
                        <h5>Salary Paid</h5>
                        <h2>₹ {{ number_format($monthlySalaryPaid,2) }}</h2>
                        <i class="mdi mdi-cash icon-lg text-success"></i>
                    </div>
                </div>
            </div>

        </div>


        {{-- =======================
            CHARTS
        ========================--}}
        <div class="row">

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Income vs Expense</h4>
                        <div style="height:300px;">
                            <canvas id="incomeExpenseChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Daily Production (kg)</h4>
                        <div style="height:300px;">
                            <canvas id="productionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        {{-- =======================
            RECENT TRANSACTIONS & ALERTS
        ========================--}}
        <div class="row">

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title">Recent Transactions</h4>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentEntries as $entry)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($entry->date)->format('d M, Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $entry->type == 'income' ? 'success' : 'danger' }}">
                                                {{ ucfirst($entry->type) }}
                                            </span>
                                        </td>
                                        <td class="text-right d-flex align-items-center justify-content-end">
                                            @if($entry->type == 'income')
                                                <i class="mdi mdi-arrow-up text-success mr-1"></i>
                                            @else
                                                <i class="mdi mdi-arrow-down text-danger mr-1"></i>
                                            @endif
                                            ₹{{ number_format($entry->amount, 2) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No transactions found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card border-warning">
                    <div class="card-body">
                        <h4 class="card-title text-warning"><i class="mdi mdi-alert-outline mr-2"></i>Low Stock Alerts</h4>
                        <p class="card-description">Products with stock under 50 units.</p>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-right">Current Stock</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lowStockItems as $item)
                                    <tr>
                                        <td>
                                            <i class="mdi mdi-package-variant-closed text-muted mr-2"></i>
                                            {{ $item->product->name }}
                                        </td>
                                        <td class="text-right text-danger font-weight-bold">
                                            {{ number_format($item->current_stock, 2) }} {{ $item->product->unit ?? 'kg' }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">All stocks are optimal.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
</div>
@endsection



@push('scripts')
<script>

document.addEventListener('DOMContentLoaded', function () {

    /* ==========================
        INCOME VS EXPENSE CHART
    ===========================*/
    new Chart(document.getElementById('incomeExpenseChart'), {

type: 'line',

data: {
    labels: Array.from({length: {{ count($chartIncome) }}}, (_, i) => i + 1),

    datasets: [
        {
            label: 'Income',
            data: @json($chartIncome),
            borderColor: '#00d25b',
            backgroundColor: 'rgba(0,210,91,0.1)',
            tension: 0.3
        },
        {
            label: 'Expense',
            data: @json($chartExpense),
            borderColor: '#fc424a',
            backgroundColor: 'rgba(252,66,74,0.1)',
            tension: 0.3
        }
    ]
},

options: {
    responsive: true,
    maintainAspectRatio: false,
    animation: false,
    scales: {
        y: {
            beginAtZero: true
        }
    }
}

});



    /* ==========================
        PRODUCTION CHART
    ===========================*/
    new Chart(document.getElementById('productionChart'), {

        type: 'bar',

        data: {
            labels: @json($chartProduction->pluck('date')),

            datasets: [
                {
                    label: 'Production (kg)',
                    data: @json($chartProduction->pluck('qty')),
                    backgroundColor: '#0090e7'
                }
            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }

    });

});
</script>
@endpush

