@extends('layouts.app')

@section('content')
<div class="container">

    <h2 class="mb-4">Dashboard</h2>

    {{-- Summary Cards --}}
    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Income (This Month)</h5>
                    <h3>₹ {{ number_format($monthlyIncome, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Expense (This Month)</h5>
                    <h3>₹ {{ number_format($monthlyExpense, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5>Balance</h5>
                    <h3>₹ {{ number_format($balance, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5>Total Production (kg)</h5>
                    <h3>{{ number_format($monthlyProduction, 2) }}</h3>
                </div>
            </div>
        </div>

    </div>
    {{-- Charts Section --}}
    <div class="row mb-4">

        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <h5>Workers</h5>
                    <h3>{{ $workerCount }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5>Uddhar (This Month)</h5>
                    <h3>₹ {{ number_format($monthlyUddhar, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5>Salary Paid</h5>
                    <h3>₹ {{ number_format($monthlySalaryPaid, 2) }}</h3>
                </div>
            </div>
        </div>

    </div>
    {{-- Additional charts or content can be added here --}}
    <div class="card mb-4">
    <div class="card-header">Income vs Expense (This Month)</div>
    <div class="card-body">
        <canvas id="incomeExpenseChart"></canvas>
    </div>
    </div>

    <script>
        new Chart(document.getElementById('incomeExpenseChart'), {
            type: 'line',
            data: {
                labels: [...Array({{ count($chartIncome) }}).keys()].map(i => i + 1),
                datasets: [
                    {
                        label: "Income",
                        data: @json($chartIncome),
                        borderColor: "green",
                        fill: false
                    },
                    {
                        label: "Expense",
                        data: @json($chartExpense),
                        borderColor: "red",
                        fill: false
                    }
                ]
            }
        });
    </script>
    <div class="card mb-4">
    <div class="card-header">Daily Production (kg)</div>
    <div class="card-body">
        <canvas id="productionChart"></canvas>
    </div>
    </div>

    <script>
        new Chart(document.getElementById('productionChart'), {
            type: 'bar',
            data: {
                labels: @json($chartProduction->pluck('date')),
                datasets: [
                    {
                        label: "Production (kg)",
                        data: @json($chartProduction->pluck('qty')),
                        backgroundColor: "blue"
                    }
                ]
            }
        });
    </script>

@endsection