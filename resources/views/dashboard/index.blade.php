@extends('layouts.app')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        {{-- TOP SUMMARY CARDS --}}
        <div class="row">

            {{-- Income --}}
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h3 class="mb-0">₹ {{ number_format($monthlyIncome, 2) }}</h3>
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
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h3 class="mb-0">₹ {{ number_format($monthlyExpense, 2) }}</h3>
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

            {{-- Balance --}}
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h3 class="mb-0">₹ {{ number_format($balance, 2) }}</h3>
                            </div>
                            <div class="col-3">
                                <div class="icon icon-box-primary">
                                    <span class="mdi mdi-wallet icon-item"></span>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-muted font-weight-normal">Balance</h6>
                    </div>
                </div>
            </div>

            {{-- Production --}}
            <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h3 class="mb-0">{{ number_format($monthlyProduction, 2) }} kg</h3>
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

        {{-- SECOND ROW --}}
        <div class="row">

            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5>Workers</h5>
                        <h2>{{ $workerCount }}</h2>
                        <i class="mdi mdi-account-group icon-lg text-info"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5>Uddhar (This Month)</h5>
                        <h2>₹ {{ number_format($monthlyUddhar, 2) }}</h2>
                        <i class="mdi mdi-alert-circle icon-lg text-danger"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5>Salary Paid</h5>
                        <h2>₹ {{ number_format($monthlySalaryPaid, 2) }}</h2>
                        <i class="mdi mdi-cash icon-lg text-success"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- CHARTS --}}
        <div class="row">

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Income vs Expense</h4>
                        <canvas id="incomeExpenseChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Daily Production (kg)</h4>
                        <canvas id="productionChart"></canvas>
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

    new Chart(document.getElementById('incomeExpenseChart'), {
        type: 'line',
        data: {
            labels: [...Array({{ count($chartIncome) }}).keys()].map(i => i + 1),
            datasets: [
                {
                    label: 'Income',
                    data: @json($chartIncome),
                    borderColor: '#00d25b',
                    fill: false
                },
                {
                    label: 'Expense',
                    data: @json($chartExpense),
                    borderColor: '#fc424a',
                    fill: false
                }
            ]
        }
    });

    new Chart(document.getElementById('productionChart'), {
        type: 'bar',
        data: {
            labels: @json($chartProduction->pluck('date')),
            datasets: [{
                label: 'Production (kg)',
                data: @json($chartProduction->pluck('qty')),
                backgroundColor: '#0090e7'
            }]
        }
    });

});
</script>
@endpush

